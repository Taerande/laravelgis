<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'shop_id',
        'name',
        'description',
        'img_url'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'menu_id', 'id');
    }
}
