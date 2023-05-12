<?php

namespace App\Http\Controllers;

use App\Models\Edge;
use App\Models\Node;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SplPriorityQueue;

class NodeEdgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    //  graph : 범위
    // source : 출발
    //  target : 도착
    private function dijkstra($graph, $source, $target) {

    $dist = [];
    $prev = [];
    $queue = new SplPriorityQueue();
    $visited = [];

    foreach ($graph as $node => $adj) {
        $dist[$node] = INF;
        $prev[$node] = null;
        $visited[$node] = false;
    }

    $dist[$source] = 0;
    $queue->insert($source, 0);

    while (!$queue->isEmpty()) {
        // Get the node with the smallest distance
        $v = $queue->extract();

        // Stop searching when the target node is extracted
        if ($v === $target) {
            break;
        }

        // Skip if the node has already been visited
        if ($visited[$v]) {
            continue;
        }

        $visited[$v] = true;

        // Process neighbors
        foreach ($graph[$v] as $adj) {
            $u = $adj['to'];
            $weight = $adj['weight'];

            if (!$visited[$u]) {
                $alt = $dist[$v] + $weight;

                if ($alt < $dist[$u]) {
                    $dist[$u] = $alt;
                    $prev[$u] = $v;
                    $queue->insert($u, $alt);
                }
            }
        }
    }

    // Compute the shortest path
    $path = [];
    $u = $target;

    while (isset($prev[$u])) {
        array_unshift($path, $u);
        $u = $prev[$u];
    }

    if ($path) {
        array_unshift($path, $source);
    }

    // Return the shortest path and distance
    return ['path' => $path, 'distance' => $dist[$target]];


    }

    private function getNearestNode($longitude, $latitude)
    {
        // 반경 300m내 가장 가까운 노드 검색
        $point = "ST_GeomFromText('POINT($longitude $latitude)')";
        return Node::whereRaw("ST_DWithin(node, $point, 1000)")
           ->orderByRaw("ST_Distance(node, $point)")
           ->first();

    }
    public function index(Request $request)
    {
        $connectedNodeIds = Edge::pluck('from_node_id')->merge(Edge::pluck('to_node_id'))->unique();

        // Node 모델에서 연결되지 않은 노드 가져오기
        $unconnectedNodes = Node::whereNotIn('id', $connectedNodeIds)->get();

        return response($unconnectedNodes, 200);
    }
    public function getPath(Request $request)
    {

        // shop 에서 가장 가까운 Node 값을 찾고 이를  $source로 해야함.
        $shop = Shop::select(DB::raw('ST_AsGeoJSON(location) as location'))->find($request->shop_id);
        $shopGeo = json_decode($shop->location);

        $startNode = $this->getNearestNode($shopGeo->coordinates[0], $shopGeo->coordinates[1]);


        // 목적지
        $dest = $request->destination;
        $endNode = $this->getNearestNode($dest['lng'], $dest['lat']);

        // 상점과 주문자 사이에 범위 설정
        // 현재 구불길 같은게 너무 심해서 출발 도착 지점사이의 직사각형 만드는걸 좀 더 크게 만들 필요가 있다. 적당값 찾는게 관건이다.
        $minLng = min($shopGeo->coordinates[0], $dest['lng']) - 0.0005;
        $minLat = min($shopGeo->coordinates[1], $dest['lat']) - 0.0005;
        $maxLng = max($shopGeo->coordinates[0], $dest['lng']) + 0.0005;
        $maxLat = max($shopGeo->coordinates[1], $dest['lat']) + 0.0005;


        // 폐곡선 생성
        $envelope = "ST_SetSRID(ST_MakePolygon(ST_GeomFromText('LINESTRING($minLng $maxLat, $maxLng $maxLat, $maxLng $minLat, $minLng $minLat, $minLng $maxLat)')),4326)";

        // 생성된 폐곡선 내 존재하는 node들의 id 값들
        $nodes_id = Node::select('id')->whereRaw("ST_Contains($envelope, node::geometry)")->get();

        // node id값들을 fk로 가지고 있는 모든 edges
        $edges = Edge::whereIn('from_node_id', $nodes_id)->orWhereIn('to_node_id', $nodes_id)->get();


        // dijkstra 알고리즘에 적용할 무방향성 그래프
        $graph = [];

        foreach ($edges as $edge) {
            $from = $edge->from_node_id;
            $to = $edge->to_node_id;
            $weight = $edge->weight;

            if (!isset($graph[$from])) {
                $graph[$from] = [];
            }
            if (!isset($graph[$to])) {
                $graph[$to] = [];
            }

            $graph[$from][] = ['to' => $to, 'weight' => $weight];
            $graph[$to][] = ['to' => $from, 'weight' => $weight];
        }


        $result = $this->dijkstra($graph, $startNode->id, $endNode->id);



        $pathNodes = Node::select('id', 'name', DB::raw('ST_AsGeoJSON(node) as node'))
        ->whereIn('id', $result['path'])
        ->orderByRaw("array_position(ARRAY" . json_encode($result['path']) . "::int[], id::int)")
        ->get();



         $pathNodes = $pathNodes->map( function ($node) {
            $node->node = json_decode($node->node);
            return $node;
        });

        return response($pathNodes, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $lat = $request->lat;
        $lng = $request->lng;


        $point = DB::raw("ST_GeomFromText('POINT($lng $lat)')");

        $node = new Node([
            'name' => $request->name,
            'node' => $point,
        ]);

        $node->save();

        return response([
            'result' => [
                'id' => $node->id,
                'name' => $request->name,
                'lat' => $request->lat,
                'lng' => $request->lng,
            ]
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
