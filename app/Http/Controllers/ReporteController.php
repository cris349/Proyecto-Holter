<?php

namespace App\Http\Controllers;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {}
}
