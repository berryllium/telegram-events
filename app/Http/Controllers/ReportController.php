<?php

namespace App\Http\Controllers;

use App\Models\TelegramBot;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.index', [
            'authors' => TelegramBot::findOrFail(session('bot'))->authors
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'from' => 'required',
            'to' => 'required',
            'type' => 'required'
        ]);

        $data = $request->toArray();

        $report = ucfirst($data['type']);
        $class = "\\App\\Actions\\Report\\{$report}Report";
        if (class_exists($class)) {
            return (new $class)->handle($data);
        }

        if($request->wantsJson()) {
            return response()->json(['error' => "class $class not found"], 500);
        }

        return back()->with('error', __('webapp.error'));
    }
}
