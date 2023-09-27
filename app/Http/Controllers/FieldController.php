<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\Field;
use App\Models\Form;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Form $form)
    {

        return view('field/index', ['fields' => $form->fields()->paginate(20), 'form' => $form]);
    }

    /**
     * Show the field for creating a new resource.
     */
    public function create(Form $form)
    {
        return view('field/create', ['form' => $form]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Form $form, Request $request)
    {
        $form->fields()->save(
            Field::make(
                $request->validate([
                    'name' => 'required',
                    'code' => 'required',
                    'type' => 'required|in:' . implode(',', array_keys(Field::$types))
                ])
            )
        );
        return redirect(route('field.index', ['form' => $form]))->with('success', 'Поле успешно создано!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the field for editing the specified resource.
     */
    public function edit(Form $form, Field $field)
    {
        return view('field/edit', [
            'form' => $form,
            'field' => $field,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Form $form, Field $field)
    {
        $field->update($request->validate([
            'name' => 'required|min:2',
            'code' => 'required',
        ]));

        if($dictionary_id = (int) $request->get('dictionary_id')) {
            $field->dictionary_id = $dictionary_id;
            $field->save();
        }
        return redirect(route('form.edit', $form))->with('success', 'Поле успешно обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form, Field $field)
    {
        $field->delete();
        return redirect(route('field.index', ['form' => $form]))->with('success', 'Поле успешно удалено!');
    }
}
