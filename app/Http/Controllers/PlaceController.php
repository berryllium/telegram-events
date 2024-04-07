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
            'appeal_text' => '',
            'working_hours' => '',
            'additional_info' => '',
            'tag_set' => '',
            'domain' => 'nullable|unique:places,domain',
            'email' => 'max:255',
            'phone' => 'max:255',
            'link_whatsapp' => 'max:255',
            'link_tg' => 'max:255',
            'link_ok' => 'max:255',
            'link_vk' => 'max:255',
            'link_instagram' => 'max:255',
            'seo_title' => '',
            'seo_description' => '',
            'header_script' => '',
        ]);

        $place = TelegramBot::find(session('bot'))->places()->create($data);

        $channels = array_filter($request->input('channels'));
        $place->channels()->sync($channels);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if($file->getError()) {
                return back()->with('error',$file->getErrorMessage());
            }
            $path = $file->store('public/media');
            $place->update(['image' => $path]);
        }

        if ($request->hasFile('logo_image')) {
            $file = $request->file('logo_image');
            if($file->getError()) {
                return back()->with('error',$file->getErrorMessage());
            }
            $path = $file->store('public/media');
            $place->update(['logo_image' => $path]);
        }

        if ($request->hasFile('appeal_image')) {
            $file = $request->file('appeal_image');
            if($file->getError()) {
                return back()->with('error',$file->getErrorMessage());
            }
            $path = $file->store('public/media');
            $place->update(['appeal_image' => $path]);
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
            'template' => '',
            'address' => 'required|min:2|max:255',
            'address_link' => '',
            'description' => 'max:1000',
            'appeal_text' => '',
            'working_hours' => '',
            'additional_info' => '',
            'tag_set' => '',
            'domain' => 'nullable|max:255|unique:places,domain,' . $place->id,
            'email' => 'max:255',
            'phone' => 'max:255',
            'link_whatsapp' => 'max:255',
            'link_tg' => 'max:255',
            'link_ok' => 'max:255',
            'link_vk' => 'max:255',
            'link_instagram' => 'max:255',
            'seo_title' => '',
            'seo_description' => '',
            'header_script' => '',
        ]));

        $place->channels()->sync($request->input('channels') ?: []);

        foreach ($place->place_files as $f) {
            $f->delete();
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if($file->getError()) {
                return back()->with('error',$file->getErrorMessage());
            }
            $path = $file->store('public/media');
            $place->update(['image' => $path]);
        }

        if ($request->hasFile('logo_image')) {
            $file = $request->file('logo_image');
            if($file->getError()) {
                return back()->with('error',$file->getErrorMessage());
            }
            $path = $file->store('public/media');
            $place->update(['logo_image' => $path]);
        }

        if ($request->hasFile('appeal_image')) {
            $file = $request->file('appeal_image');
            if($file->getError()) {
                return back()->with('error',$file->getErrorMessage());
            }
            $path = $file->store('public/media');
            $place->update(['appeal_image' => $path]);
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
