<?php

namespace App\Admin\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home.index');
    }


}