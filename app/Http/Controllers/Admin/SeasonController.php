<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::latest()->get();
        return view('admin.seasons.index', compact('seasons'));
    }

    public function create()
    {
        return view('admin.seasons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|in:mid,high',
        ]);

        Season::create($data);

        return redirect()->route('admin.seasons.index')->with('flash.banner', 'Temporada guardada con éxito.');
    }

    public function edit(Season $season)
    {
        return view('admin.seasons.edit', compact('season'));
    }

    public function update(Request $request, Season $season)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|in:mid,high',
        ]);

        $season->update($data);

        return redirect()->route('admin.seasons.index')->with('flash.banner', 'Temporada actualizada.');
    }

    public function destroy(Season $season)
    {
        $season->delete();
        return redirect()->route('admin.seasons.index')->with('flash.banner', 'Temporada eliminada.');
    }
}
