<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Channel::class, 'channel');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('channel/index', ['channels' => Channel::query()->where('telegram_bot_id', session('bot'))->paginate(20)]);
    }

    /**
     * Show the channel for creating a new resource.
     */
    public function create()
    {
        return view('channel/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:2',
            'tg_id' => 'required|int',
            'type' => 'required|in:tg,vk',
            'description' => 'max:1000',
        ]);
        $data['telegram_bot_id'] = session('bot');

        Channel::create($data);

        return redirect(route('channel.index'))->with('success', __('webapp.record_added'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the channel for editing the specified resource.
     */
    public function edit(Channel $channel)
    {
        return view('channel/edit', [
            'channel' => $channel
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Channel $channel)
    {
        $channel->update($request->validate([
            'name' => 'required|min:2',
            'tg_id' => 'required|int',
            'type' => 'required|in:tg,vk',
            'description' => 'max:1000',
        ]));
        return back()->with('success', __('webapp.record_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel)
    {
        $channel->delete();
        return redirect(route('channel.index'))->with('success', __('webapp.record_deleted'));
    }
}
