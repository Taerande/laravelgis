<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edge extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'from_node_id',
        'to_node_id',
        'weight'
    ];

    public function fromNode()
    {
        return $this->belongsTo(Node::class, 'from_node_id', 'id');
    }

    public function toNode()
    {
        return $this->belongsTo(Node::class, 'to_node_id', 'id');
    }
}
