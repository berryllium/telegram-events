<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageFile;
use App\Models\MessageSchedule;
use App\Rules\ValidMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Message::class, 'message');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'search',
            'status',
        ]);
        $filters['telegram_bot'] = session('bot');

        return view('message.index', [
            'schedules' => MessageSchedule::with('message.author')->filter($filters)->paginate(20),
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
        /**
         * @var MessageFile $file
         */

        $message->allowed = (bool) $request->get('allowed');

        $message->update($request->validate(
            ['text' => ['required', 'max:1000', new ValidMessage()]],
            ['files.*' => 'mimes:jpeg,jpg,png,webp,mp4,avi,mkv|max:50000'],
        ));

        foreach ($message->message_files as $file) {
            if (!in_array($file->id, $request->get('current_files'))) {
                $file->delete();
            }
        }


        if ($files = $request->file('files')) {
            foreach ($files as $file) {
                if($file->getError()) {
                    return back()->with('error',$file->getErrorMessage());
                }
                $path = $file->store('public/media');
                $message->message_files()->save(new MessageFile(['filename' => $path]));
            }
        }


        return back()->with('success', __('webapp.record_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();
        return redirect(route('messages.index'))->with('success', __('webapp.message_deleted'));
    }
}
