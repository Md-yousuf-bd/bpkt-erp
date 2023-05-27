<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    //
    public function index()
    {
        //
        return redirect(route('home'));
        //return view('site_under_construction');
    }
}
