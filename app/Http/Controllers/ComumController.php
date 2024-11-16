<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ComumController extends Controller
{
    public function index() : View {
        $view = 'dashboard';
        return view('comum.index', compact('view'));
    }
}
