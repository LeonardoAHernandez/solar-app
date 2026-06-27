<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groupedAccommodations = Accommodation::where('status', 2)
        ->get()
        ->groupBy('category');

        return view('visitor.accommodations.index', compact('groupedAccommodations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Buscamos el registro de forma manual por ID o por Slug
        $accommodation = \App\Models\Accommodation::where('id', $id)
            ->orWhere('slug', $id)
            ->with(['images', 'services', 'details', 'tags'])
            ->firstOrFail(); // Lanza un error 404 si no existe, ideal para producción

        return view('visitor.accommodations.show', compact('accommodation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accommodation $accommodation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accommodation $accommodation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accommodation $accommodation)
    {
        //
    }
}
