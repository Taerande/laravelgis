<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'node',
    ];

    public function edges()
    {
        return $this->hasMany(Edge::class, 'from_node_id', 'id')->where('to_node_id', $this->id);
    }
}
