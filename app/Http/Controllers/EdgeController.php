<?php

namespace App\Http\Controllers;

use App\Models\Edge;
use App\Models\Node;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EdgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $edges = Edge::all();
        // $fromNode = Node::where('from_node_id',$request->from)->get();
        // $toNode = Node::find($request->to);

        return response($edges,200);


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
        //

        $fromNode = Node::find($request->from);
        $toNode = Node::find($request->to);


        if (!$fromNode || !$toNode) {
            return response(['error' => 'Invalid node ID'], 400);
        }

        $edge = new Edge([
            'weight' => $request->weight,
            'from_node_id' => $fromNode->id,
            'to_node_id' => $toNode->id
        ]);

        $edge->save();

        return response($edge, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Edge  $edge
     * @return \Illuminate\Http\Response
     */
    public function show(Edge $edge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Edge  $edge
     * @return \Illuminate\Http\Response
     */
    public function edit(Edge $edge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Edge  $edge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Edge $edge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Edge  $edge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Edge $edge)
    {
        //
    }
}
