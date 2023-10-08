<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\TelegramBot;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Form::class, 'form');
    }

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
        return redirect(route('form.index'))->with('success', __('webapp.record_added'));
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
        return back()->with('success', __('webapp.record_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        if($bot = TelegramBot::query()->where('form_id', $form->id)->first()) {
            return back()->with('error', __('webapp.forms.form_has_bot', ['bot' => $bot->name]));
        } else {
            $form->delete();
            return redirect(route('form.index'))->with('success', __('webapp.record_deleted'));
        }
    }
}
