<?php

namespace App\Providers;

use App\Models\Web\Notices;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class WebComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
{
    View::composer('*', function ($view) {

        // Footer slider images
        $footerSliderImage = DB::table('web_footer_image')
            ->select('image')
            ->orderBy('image_order', 'ASC')
            ->get()
            ->toArray();

        // Active notices
        $noticeLists = Notices::where('is_active', 1)
            ->orderBy('updated_dt_tm', 'DESC')
            ->get();

        // Prayer time
        $prayerTime = function () {
            $crrDate = Carbon::now()->format('Y-m-d');

            $row = DB::table('web_prayer_time')->first(); // Assuming id=1

            if (!$row) {
                return null;
            }

            $prayerArr = [
                'fajr'       => $row->fajr,
                'zuhor'      => $row->zuhor,
                'asor'       => $row->asor,
                'maghrib'    => $row->maghrib,
                'isha'       => $row->isha,
                'jumma'      => $row->jumma,
                'sunrise'    => $row->sunrise,
                'sunset'     => $row->sunset,
                'prayer_date'=> $crrDate,
            ];

            if ($row->prayer_date == $crrDate) {
                return $prayerArr;
            }

            // Fetch new prayer times from library
            $prayerLibrary = app()->make('App\Libraries\PrayerLibrary');
            $responsePrayerTime = $prayerLibrary->getDateWisePrayerTime([
                'prayerDate' => Carbon::now()->format('d-m-Y'),
                'dataSource' => $row->data_source,
            ]);

            $prayerTimeObj = json_decode($responsePrayerTime);

            if ($prayerTimeObj && $prayerTimeObj->code == 200) {
                $prayerArr['sunrise'] = Carbon::parse($prayerTimeObj->data->timings->Sunrise)->format('h:i A');
                $prayerArr['sunset']  = Carbon::parse($prayerTimeObj->data->timings->Sunset)->format('h:i A');

                if ($row->data_source != 1) {  // custom source
                    $prayerArr['fajr']    = Carbon::parse($prayerTimeObj->data->timings->Fajr)->format('h:i A');
                    $prayerArr['zuhor']   = Carbon::parse($prayerTimeObj->data->timings->Dhuhr)->format('h:i A');
                    $prayerArr['asor']    = Carbon::parse($prayerTimeObj->data->timings->Asr)->format('h:i A');
                    $prayerArr['maghrib'] = Carbon::parse($prayerTimeObj->data->timings->Maghrib)->format('h:i A');
                    $prayerArr['isha']    = Carbon::parse($prayerTimeObj->data->timings->Isha)->format('h:i A');
                }
            }

            // Update database
            $row->update([
                'fajr' => $prayerArr['fajr'],
                'zuhor' => $prayerArr['zuhor'],
                'asor' => $prayerArr['asor'],
                'maghrib' => $prayerArr['maghrib'],
                'isha' => $prayerArr['isha'],
                'sunrise' => $prayerArr['sunrise'],
                'sunset' => $prayerArr['sunset'],
                'prayer_date' => $crrDate,
                'updated_by' => 'system',
                'updated_dt_tm' => Carbon::now()->toDateTimeString(),
            ]);

            return $prayerArr;
        };

        // Pass variables to all views
        $view->with([
            'footerSliderImage' => $footerSliderImage,
            'noticeLists' => $noticeLists,
            'prayerTime' => $prayerTime(), // call function
        ]);
    });
}
}
