<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Admin;


class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
}
