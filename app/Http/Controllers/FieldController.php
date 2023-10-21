<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\Field;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FieldController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Field::class, 'field');
    }

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
        return view('field/create', [
            'form' => $form,
            'dictionaries' => Dictionary::query()->where('telegram_bot_id', session('bot'))->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Form $form, Request $request)
    {
        /** @var Field $field */
        $data = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'sort' => 'int',
            'type' => 'required|in:' . implode(',', array_keys(Field::$types))
        ]);
        $data['required'] = !!$request->get('required');
        $field = $form->fields()->save(
            Field::make(
                $data
            )
        );
        if($field->canHaveDictionary && $dictionary_id = (int) $request->get('dictionary_id')) {
            $field->dictionary_id = $dictionary_id;
            $field->save();
        }
        return redirect(route('form.edit', ['form' => $form]))->with('success', __('webapp.record_added'));
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
            'dictionaries' => Dictionary::query()->where('telegram_bot_id', session('bot'))->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Form $form, Field $field)
    {
        $rules = [
            'name' => 'required|min:2',
            'sort' => 'required|int',
            'code' => 'required',
            'required' => ''
        ];

        $data = $request->toArray();
        $data['required'] = isset($data['required']);

        if($field->type == 'place' || $field->type == 'address') {
            $data['code'] = $field->type;
        } else {
            $data['code'] = $request->get('code');
        }

        $field->update(Validator::validate($data, $rules));

        if($dictionary_id = (int) $request->get('dictionary_id')) {
            $field->dictionary_id = $dictionary_id;
            $field->save();
        }
        return redirect(route('form.edit', $form))->with('success', __('webapp.record_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form, Field $field)
    {
        $field->delete();
        return redirect(route('form.edit', ['form' => $form]))->with('success', __('webapp.record_deleted'));
    }
}
