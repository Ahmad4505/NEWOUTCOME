<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
  public function index($page)
{
    if (view()->exists($page)) {
        return view($page);
    } else {
        return view('404');
    }
}
}
