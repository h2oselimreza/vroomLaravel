<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\PrayerTimeRequest;
use App\Models\WebPrayerTime;
use Illuminate\Http\Request;

class PrayerTimeController extends Controller
{
    public function index(){
        $data = WebPrayerTime::get();
        return view('admin.web.prayer-time.index',compact('data'));
    }

    public function edit($id){
        $data = WebPrayerTime::find($id);
        return view('admin.web.prayer-time.edit',compact('data'));
    }

    public function update(PrayerTimeRequest $request, WebPrayerTime $webPrayerTime)
    {
        $webPrayerTime->update([
            'fajr'          => $request->fajr,
            'zuhor'         => $request->zuhor,
            'asor'          => $request->asor,
            'maghrib'       => $request->maghrib,
            'isha'          => $request->isha,
            'jumma'         => $request->jumma,
            'data_source'   => $request->data_source,
        ]);

        return redirect()
            ->route('admin.prayer-time.module')
            ->with('success', 'Prayer time updated successfully.');
    }
}
