<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Slider;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Place $place, string $type)
    {
        $slider = $place->sliders()->where('type', $type)->first();
        if($slider) {
            return redirect(route('slider.edit', [
                'place' => $place,
                'slider' => $slider,
                'type' => $type
            ]));
        }

        return view('slider.create', [
            'type' => $type,
            'place' => $place
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Place $place, Request $request)
    {
        $data = $request->toArray();
        /** @var Slider $slider */
        $slider = $place->sliders()->create($request->validate([
            'type' => 'required',
        ]));
        $this->saveSlides($slider, $request);
        return redirect(route('place.edit', ['place' => $place]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place, string $type, Slider $slider)
    {
        return view('slider.edit', [
            'type' => $type,
            'place' => $place,
            'slider' => $slider->load('slides')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Place $place, string $type, Slider $slider, Request $request)
    {
        $data = $request->toArray();
        $old_slides = $request->get('old_id') ?? [];
        foreach ($slider->slides as $slide) {
            /** @var Slide $slide */
            if(!in_array($slide->id, $old_slides)) {
                $slide->delete();
            } else {
                $files = $request->file('old_files');
                $file = $files[$slide->id] ?? null;
                if($file) {
                    Storage::delete($slide->filename);
                    $path = $file->store('public/media');
                }
                $slide->update([
                    'name' => $data['old_name'][$slide->id],
                    'link' => $data['old_link'][$slide->id],
                    'filename' => $path ?? $slide->filename,
                ]);
            }
        }
        $this->saveSlides($slider, $request);
        return redirect()->back()->with('success', __('webapp.success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        //
    }

    private function saveSlides(Slider $slider, Request $request): void
    {
        $data = $request->toArray();
        if($request->hasFile('files')) {
            foreach ($request->file('files') as $k => $file) {
                if($file->getError()) {
                    back()->with('error', $file->getErrorMessage());
                    return;
                }
                $path = $file->store('public/media');
                $slider->slides()->save(new Slide([
                    'filename' => $path,
                    'name' => $data['name'][$k],
                    'link' => $data['link'][$k],
                ]));
            }
        }
    }
}
