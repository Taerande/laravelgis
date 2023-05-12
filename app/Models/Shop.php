<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'contact',
        'address',
        'status',
        'location',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class,'admin_id','id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class,'menu_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id','id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'shop_id', 'id');
    }

}
