<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dictionary.index', ['dictionaries' => Dictionary::paginate(20)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dictionary.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dictionary = Dictionary::create($request->validate([
            'name' => 'required|max:255',
        ]));
        return redirect(route('dictionary.edit', $dictionary))->with('success', __('webapp.dictionary.success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Dictionary $dictionary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dictionary $dictionary)
    {
        return view('dictionary.edit', ['dictionary' => $dictionary]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dictionary $dictionary)
    {
        $dictionary->update($request->validate([
            'name' => 'required|max:255',
        ]));
        return redirect(route('dictionary.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dictionary $dictionary)
    {
        $dictionary->delete();
        return back();
    }
}
