<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('form/index', ['forms' => Form::paginate(20)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('form/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Form::create($request->validate([
            'name' => 'required|min:2',
            'description' => 'max:1000',
        ]));
        return redirect(route('form.index'))->with('success', 'Форма успешно создана!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        return view('form/edit', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Form $form)
    {
        $form->update($request->validate([
            'name' => 'required|min:2',
            'template' => 'max:1000',
            'description' => 'max:1000',
        ]));
        return back()->with('success', 'Форма успешно обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        $form->delete();
        return redirect(route('form.index'))->with('success', 'Форма успешно удалена!');
    }
}
