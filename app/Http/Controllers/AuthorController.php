<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Place;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('author.index', [
            'authors' => Author::paginate(20)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('author.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Author::create($request->validate([
            'name' => 'required',
            'username' => 'required',
            'tg_id' => 'required|int',
            'description' => 'required',
            'trusted' => 'bool'
        ]));
        return redirect(route('author.index'))->with('success', __('webapp.record_added'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        return view('author.edit', [
            'author' => $author,
            'places' => Place::with('form')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $author->update($request->validate([
            'name' => 'required',
            'username' => 'required',
            'tg_id' => 'required|int',
            'description' => '',
            'trusted' => 'bool'
        ]));

        $author->places()->sync($request->get('places'));

        return back()->with('success', __('webapp.record_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $author->messages()->delete();
        $author->delete();
        return back()->with('success', 'webapp.record_deleted');
    }
}
