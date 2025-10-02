<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    //
     public function index()
    {
        return view('driver.details.menu');
    }

    public function cisterns()
    {
        return view('driver.details.details');
    }

    public function dryBox()
    {
        return view('driver.details.drybox');
    }

    public function platform()
    {
        return view('driver.details.platform');
    }

    public function pneumatic()
    {
        return view('driver.details.pneumatic');
    }
}
