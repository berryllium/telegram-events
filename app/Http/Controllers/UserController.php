<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\TelegramBot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct() {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('user.index', [
            'bots' => TelegramBot::all(),
            'roles' => Role::all(),
            'users' => User::filter($request->only([
                'search',
                'telegram_bot',
                'role',
            ]))->paginate(20)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create', [
            'roles' => Role::all(),
            'bots' => TelegramBot::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $user->roles()->sync($request->input('roles'));
        $user->telegram_bots()->sync($request->input('telegram_bots'));

        return back()->with('success', __('webapp.record_added'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user.edit', [
            'user' => $user,
            'roles' => Role::all(),
            'bots' => TelegramBot::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $constrains = [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
        ];
        if($request->get('password') || $request->get('password_confirmation')) {
            $constrains['password'] = 'required|confirmed|min:6';
        }
        $user->update($request->validate($constrains));
        $user->roles()->sync($request->input('roles'));
        $user->telegram_bots()->sync($request->input('bots'));

        return back()->with('success', __('webapp.record_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'webapp.record_deleted');
    }
}
