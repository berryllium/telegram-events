<?php

namespace App\Http\Controllers;

use App\Actions\Report\AuthorReport;
use App\Actions\Report\PlaceReport;
use App\Models\TelegramBot;
use Carbon\Carbon;
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

        return back()->with('error', __('webapp.error'));
    }
}
