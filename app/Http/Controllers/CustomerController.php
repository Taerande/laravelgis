<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // index list
    public function index()
    {
        $customer_list = Customer::all();

        return response()->json($customer_list, 200);

    }
}
