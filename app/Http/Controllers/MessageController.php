<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageFile;
use App\Models\MessageLog;
use App\Models\MessageSchedule;
use App\Models\Place;
use App\Models\User;
use App\Rules\MultibyteLength;
use App\Rules\ValidMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        /** @var User $user */
        $user = Auth::user();
        $filters = $request->only([
            'search',
            'status',
            'from',
            'to',
            'deleted'
        ]);
        $filters['telegram_bot'] = session('bot');
        if($user->hasRole('moderator') && $user->places()->count()) {
            $filters['place'] = $user->places->pluck('id');
        }

        $messages = Message::with('author', 'message_schedules')->filter($filters)->paginate(20)->withQueryString();
        $places_map = [];
        $places = Place::query()->where('telegram_bot_id', session('bot'))->get();
        foreach ($places as $place) {
            $places_map[$place->id] = $place->name;
        }

        return view('message.index', [
            'messages' => $messages,
            'places' => $places_map
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

        $has_files = $request->hasFile('files') || $request->get('current_files');
        $max_length = $has_files ? config('app.post_max_message') : config('app.post_without_files_max_message');

        $request->validate(['files.*' => 'mimes:jpeg,jpg,png,webp,mp4,avi,mkv|max:50000']);

        if($request->has('text')) {
            $message->update($request->validate(
                ['text' => ['required', new ValidMessage(), new MultibyteLength($max_length, "\r\n\r\n" . $message->telegram_bot->links)]],
            ));
        } else {
            $message->update($request->except('text'));
        }

        foreach ($message->message_files as $file) {
            if (!in_array($file->id, $request->get('current_files') ?: [])) {
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

        MessageLog::create([
            'message_id' => $message->id,
            'user_id' => auth()->user()->getAuthIdentifier(),
            'telegram_bot_id' => session('bot'),
            'action' => 'edit',
        ]);

        return back()->with('success', __('webapp.record_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        foreach ($message->message_files as $file) {
            $file->delete();
        }
        $message->delete();

        MessageLog::create([
            'message_id' => $message->id,
            'user_id' => auth()->user()->getAuthIdentifier(),
            'telegram_bot_id' => session('bot'),
            'action' => 'delete',
        ]);
        return redirect(route('message.index'))->with('success', __('webapp.messages.deleted'));
    }
}
