<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\TelegramBot;
use App\Models\TelegramBotAuthor;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('author.index', [
            'pivots' => TelegramBotAuthor::query()->where('telegram_bot_id', session('bot'))->paginate()
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

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        $bot = TelegramBot::find(session('bot'));
        $pivot = $author->telegram_bots()->wherePivot('telegram_bot_id', $bot->id)->first()->pivot;

        return view('author.edit', [
            'author' => $author,
            'pivot' => $pivot,
            'places' => $bot->places()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $bot = TelegramBot::find(session('bot'));
        $bot->authors()->updateExistingPivot($author->id, $request->validate([
            'trusted' => 'int',
            'title' => 'required|max:255',
            'description' => 'max:1000',
        ]));

        $newPlaces = $request->get('places') ?? [];
        $oldPlaces = $author->places()->where('telegram_bot_id', $bot->id)->get();

        foreach ($oldPlaces as $place) {
            if(!in_array($place->id, $newPlaces)) {
                $author->places()->detach($place);
            }
        }

        foreach ($newPlaces as $place) {
            if(!$oldPlaces->contains($place)) {
                $author->places()->attach($place);
            }
        }

        return back()->with('success', __('webapp.record_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $bot = TelegramBot::find(session('bot'));
        $bot->authors()->detach($author);
        return back()->with('success', 'webapp.record_deleted');
    }
}
