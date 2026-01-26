<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPlaceholderController extends Controller
{
    public function index(Request $request)
    {
        $title = $request->get('title', 'Módulo en Desarrollo');
        return view('admin.placeholder', compact('title'));
    }
}
