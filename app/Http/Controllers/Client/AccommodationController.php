<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Detail;
use App\Models\Service;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccommodationController extends Controller
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
        $details = Detail::all();
        $tags = Tag::all();
        $services = Service::all();

        return view('client.accommodations.edit', compact('accommodation', 'details', 'tags', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accommodation $accommodation)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:accommodations,slug,' . $accommodation->id,
            'summary' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|integer',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
            'locationURL' => 'required',
        ]);

        // if ($request->hasFile('image')) {
        //     if ($accommodation->image) {
        //         Storage::delete($accommodation->image);
        //     }

        //     $data['image_path'] = Storage::put('/images', $request->file('image'));
        // }

        $accommodation->update($data);

        // session()->flash('flash.bannerStyle', 'danger');
        session()->flash('flash.banner', 'Propiedad actualizada correctamente.');

        return redirect()->route('client.accommodations.edit', $accommodation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accommodation $accommodation)
    {
        //
    }

    public function images(Accommodation $accommodation){
        return view('client.accommodations.images', compact('accommodation'));
    }
}
