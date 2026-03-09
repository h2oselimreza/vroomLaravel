<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use App\Models\Web\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index() {
        $events = Event::where('is_active', 1)->orderBy('date','desc')->get();
        return view('website.event.index', compact('events'));
    }

    public function show($id) {
        $data = Event::find($id);
        return view('website.event.event-details', compact('data'));
    }
}
