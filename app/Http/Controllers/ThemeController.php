<?php

namespace App\Http\Controllers;

use App\Support\ResumeThemes;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function index(Request $request): View
    {
        return view('themes.browse', [
            'themes' => ResumeThemes::all(),
            'categories' => ResumeThemes::categories(),
            'themeCount' => ResumeThemes::count(),
        ]);
    }
}
