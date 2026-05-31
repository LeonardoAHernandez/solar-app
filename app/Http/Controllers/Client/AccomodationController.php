<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Detail;
use App\Models\Service;
use App\Models\Tag;
use Illuminate\Http\Request;

class AccomodationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accommodations = Accommodation::all();

        return view('client.accommodations.index', compact('accommodations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $details = Detail::all();
        $tags = Tag::all();
        $services = Service::all();

        return view('client.accommodations.create', compact('details', 'tags', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:accommodations',
            'summary' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|integer',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
            'locationURL' => 'required',
        ]);

        $accommodation = Accommodation::create($data);

        return redirect()->route('client.accommodations.edit', $accommodation);
    }

    /**
     * Display the specified resource.
     */
    public function show(Accommodation $accommodation)
    {
        //
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
