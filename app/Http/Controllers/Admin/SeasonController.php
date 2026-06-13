<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::orderBy('start_date', 'asc')->get();
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
        ]);

        Season::create($data);

        session()->flash('flash.banner', 'Temporada creada correctamente.');
        return redirect()->route('admin.seasons.index');
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
        ]);

        $season->update($data);

        session()->flash('flash.banner', 'Temporada actualizada correctamente.');
        return redirect()->route('admin.seasons.index');
    }

    public function destroy(Season $season)
    {
        $season->delete();

        session()->flash('flash.banner', 'Temporada eliminada correctamente.');
        session()->flash('flash.bannerStyle', 'danger');
        return redirect()->route('admin.seasons.index');
    }
}
