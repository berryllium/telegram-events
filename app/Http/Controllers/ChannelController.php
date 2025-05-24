<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Channel;
use App\Models\ChannelLink;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChannelController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Channel::class, 'channel');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Log::info('Graylog работает с Laravel!', ['context' => 'тест']);
        $search = $request->get('search');
        return view('channel/index', [
            'channels' => Channel::query()
                ->where('telegram_bot_id', session('bot'))
                ->when($search, fn($query) => $query->where('name', 'like', "%$search%"))
                ->paginate(20)
                ->withQueryString()
        ]);
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
            'tg_id' => 'required',
            'type' => 'required|in:' . implode(',', Channel::$types),
            'token' => '',
            'description' => 'max:1000',
            'show_place' => 'int',
            'show_address' => 'int',
            'show_work_hours' => 'int',
            'show_links' => 'int',
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
        $action = $request->input('action');

        if($action == 'attach-places') {
            $channel->places()->sync($channel->telegram_bot->places);
            return back()->with('success', __('webapp.places.assign_all_done'));
        } elseif($action == 'detach-places') {
            $channel->places()->sync([]);
            return back()->with('success', __('webapp.places.reassign_all_done'));
        }

        $token = $channel->token;
        $channel->update($request->validate([
            'name' => 'required|min:2',
            'tg_id' => 'required',
            'type' => 'required|in:' . implode(',', Channel::$types),
            'token' => '',
            'description' => 'max:1000',
            'show_place' => 'required|int',
            'show_address' => 'required|int',
            'show_work_hours' => 'required|int',
            'show_links' => 'required|int',
        ]));

        // assign links
        $new_links = $request->input('channel_links');
        $new_links_ids = [];
        foreach($new_links as $new_link) {
            $id = $new_link['id'] ?? null;
            $new_link['channel_id'] = $channel->id;
            unset($new_link['id']);
            if(!$id) {
                $link_obj = $channel->links()->create($new_link);
                $id = $link_obj->id;
            } else {
                $link_obj = ChannelLink::findOrFail($id);
                $link_obj->update($new_link);
            }
            $new_links_ids[] = $id;
        }

        $channel->links()->whereNotIn('id', $new_links_ids)->delete();

        if($channel->token && $token != $channel->token) {
            try {
                $channel->subscribe($token);
            } catch (\Exception $exception) {
                Log::error('Can not assign group', ['token' => $token]);
                return back()->with('error', __('webapp.assign_channel_error'));
            }
        }

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
