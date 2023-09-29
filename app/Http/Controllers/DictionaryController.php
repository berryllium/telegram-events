<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\DictionaryValue;
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
            'description' => ''
        ]));
        return redirect(route('dictionary.edit', $dictionary))->with('success', __('webapp.record_updated'));
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
        $new_values = array_filter($request->get('new_values') ?? []);

        if($new_values) {
            $dictionary->dictionary_values()->insert(
                array_map(function ($v) use($dictionary) {
                    return ['value' => $v, 'dictionary_id' => $dictionary->id];
                }, $new_values)
            );
        }

        if ($current_values = $request->get('dictionary_values')) {
            foreach ($current_values as $k => $v) {
                if($v) {
                    DictionaryValue::query()->where('id', $k)->update(['value' => $v]);
                } else {
                    DictionaryValue::query()->where('id', $k)->delete();
                }
            }
        }

        $dictionary->update($request->validate([
            'name' => 'required|max:255',
            'description' => ''
        ]));

        return back()->with('success', __('webapp.record_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dictionary $dictionary)
    {
        $dictionary->delete();
        return back()->with('success', 'webapp.record_deleted');
    }
}
