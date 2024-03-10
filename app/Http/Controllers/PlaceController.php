<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\PlaceFile;
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
    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('place/index', [
            'places' => Place::query()
                ->where('telegram_bot_id', session('bot'))
                ->when($search, fn($query) => $query->where('name', 'like', "%$search%"))
                ->paginate(20)
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
            'address_link' => '',
            'description' => 'max:1000',
            'working_hours' => '',
            'additional_info' => '',
            'tag_set' => '',
            'domain' => 'nullable|unique:places,domain',
            'email' => '',
            'phone' => '',
        ]);

        $place = TelegramBot::find(session('bot'))->places()->create($data);

        $channels = array_filter($request->input('channels'));
        $place->channels()->sync($channels);

        if ($request->hasFile('image')) {
            $files = $request->file('image');
            foreach ($files as $file) {
                if($file->getError()) {
                    return back()->with('error',$file->getErrorMessage());
                }
                $path = $file->store('public/media');
                $place->place_files()->save(new PlaceFile(['filename' => $path]));
            }
        }

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
            'active' => '',
            'address' => 'required|min:2|max:255',
            'address_link' => '',
            'description' => 'max:1000',
            'working_hours' => '',
            'additional_info' => '',
            'tag_set' => '',
            'domain' => 'nullable|max:255|unique:places,domain,' . $place->id,
            'email' => 'max:255',
            'phone' => 'max:255',
        ]));

        $place->channels()->sync($request->input('channels') ?: []);

        if ($request->hasFile('image')) {
            $files = $request->file('image');
            foreach ($files as $file) {
                if($file->getError()) {
                    return back()->with('error',$file->getErrorMessage());
                }
                $path = $file->store('public/media');
                $place->place_files()->save(new PlaceFile(['filename' => $path]));
            }
        }

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

    public function getPlaceTagSets(Place $place) {
        if($place->tag) {
            foreach ($place->tag->dictionary_values as $tag) {
                $set = explode(':', $tag->value, 2);
                $tag_sets[trim($set[1])] = [
                    'value' => trim($set[0]),
                    'type' => 'shop',
                ];
            }
        }
        return response()->json($tag_sets ?? []);
    }
}
