<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\TelegramBot;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Place::class, 'place');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('place/index', [
            'places' => Place::query()->where('telegram_bot_id', session('bot'))->paginate(20)
        ]);
    }

    /**
     * Show the place for creating a new resource.
     */
    public function create()
    {
        return view('place/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:2|max:255',
            'address' => 'required|min:2|max:255',
            'description' => 'max:1000',
            'working_hours' => '',
            'additional_info' => '',
        ]);

        TelegramBot::find(session('bot'))->places()->create($data);

        return redirect(route('place.index'))->with('success', __('webapp.record_added'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the place for editing the specified resource.
     */
    public function edit(Place $place)
    {
        return view('place/edit', [
            'place' => $place
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Place $place)
    {
        $place->update($request->validate([
            'name' => 'required|min:2|max:255',
            'address' => 'required|min:2|max:255',
            'description' => 'max:1000',
            'working_hours' => '',
            'additional_info' => '',
        ]));
        $place->channels()->sync($request->input('channels'));
        return back()->with('success', __('webapp.record_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        $place->delete();
        return redirect(route('place.index'))->with('success', __('webapp.record_deleted'));
    }
}
