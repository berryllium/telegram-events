<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageFile;
use App\Models\MessageSchedule;
use App\Rules\ValidMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('message.index', [
            'schedules' => MessageSchedule::with('message.author')->paginate(20),
            'statuses' => MessageSchedule::$statuses,
            'status_class' => array_combine(array_keys(MessageSchedule::$statuses), ['warning', 'danger', 'success'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        return view('message.edit', ['msg' => $message]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        $message->update($request->validate([
            'text' => ['required', 'max:1000', new ValidMessage()]
        ]));

        return back()->with('success', 'Сообщение успешно обновлено');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();
        return redirect(route('messages.index'))->with('success', 'Сообщение и все его запланированные отправки удалены');
    }
}
