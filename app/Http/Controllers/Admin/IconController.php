<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Icon;
use Illuminate\Http\Request;

class IconController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_name' => 'required|string|max:255|unique:icons,class_name',
        ]);

        Icon::create($request->all());

        return redirect()->back()->with('success', '¡Icono agregado al catálogo!');
    }
}
