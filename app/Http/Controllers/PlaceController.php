<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('place/index', ['places' => Place::with('form')->paginate(20)]);
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
            'form' => 'int'
        ]);

        $place = Form::find($data['form'])->places()->create($data);

        return redirect(route('place.index'))->with('success', 'Объект успешно создан!');
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
        $place->form_id = $request->get('form');
        $place->update($request->validate([
            'name' => 'required|min:2|max:255',
            'address' => 'required|min:2|max:255',
            'description' => 'max:1000',
            'form' => 'int'
        ]));
        $place->telegram_channels()->sync($request->input('channels'));
        return back()->with('success', 'Объект успешно обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        $place->delete();
        return redirect(route('place.index'))->with('success', 'Объект успешно удален!');
    }
}
