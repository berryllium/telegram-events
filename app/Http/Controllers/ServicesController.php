<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function index(Place $place)
    {
        return view('services.index', [
            'place' => $place
        ]);
    }

    public function save(Place $place, Request $request)
    {
        $data = $request->toArray();
        $old_services = $request->get('old_id') ?? [];

        foreach ($place->services as $service) {
            /** @var Service $service */
            if(!in_array($service->id, $old_services)) {
                Storage::delete($service->image);
                $service->delete();
            } else {
                $path = '';
                $files = $request->file('old_images');
                $file = $files[$service->id] ?? null;
                if($file) {
                    Storage::delete($service->image);
                    $path = $file->store('public/media');
                }
                $service->update([
                    'name' => $data['old_name'][$service->id],
                    'description' => $data['old_description'][$service->id],
                    'image' => $path ?: $service->image,
                ]);
            }
        }

        if($request->has('name')) {
            $files = $request->file('images');
            foreach ($request->get('name') as $k => $name) {
                if(!$name) continue;
                $path = '';
                if(isset($files[$k])) {
                    $file = $files[$k];
                    if($file->getError()) {
                        return back()->with('error', $file->getErrorMessage());
                    } else {
                        $path = $file->store('public/media');
                    }
                }

                $place->services()->save(new Service([
                    'name' => $data['name'][$k],
                    'description' => $data['description'][$k],
                    'image' => $path,
                ]));
            }
        }

        return redirect()->back()->with('success', __('webapp.success'));
    }

}
