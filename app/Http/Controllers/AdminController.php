<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index()
    {
        $adm_list = Admin::all();

        return response()->json($adm_list, 200);

    }
}
