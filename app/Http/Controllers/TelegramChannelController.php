<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\TelegramChannel;
use Illuminate\Http\Request;

class TelegramChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('channel/index', ['channels' => TelegramChannel::paginate(20)]);
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
            'description' => 'max:1000',
            'form' => ''
        ]);

       if($data['form']) {
           $form = Form::find($data['form']);
           $form->channels()->create($data);
       } else {
           TelegramChannel::create($data);
       }

        return redirect(route('channel.index'))->with('success', 'Канал успешно создан!');
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
    public function edit(TelegramChannel $channel)
    {
        return view('channel/edit', [
            'channel' => $channel
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TelegramChannel $channel)
    {
        $channel->form_id = $request->get('form');
        $channel->update($request->validate([
            'name' => 'required|min:2',
            'tg_id' => 'required|int',
            'description' => 'max:1000',
        ]));
        return redirect(route('channel.index'))->with('success', 'Канал успешно обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TelegramChannel $channel)
    {
        $channel->delete();
        return redirect(route('channel.index'))->with('success', 'Канал успешно удален!');
    }
}
