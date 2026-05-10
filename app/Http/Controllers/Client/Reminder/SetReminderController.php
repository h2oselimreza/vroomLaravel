<?php

namespace App\Http\Controllers\Client\Reminder;

use App\Http\Controllers\Controller;
use App\Repositories\Client\ReminderRepository;
use App\Repositories\Client\VehicleRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetReminderController extends Controller
{

    public function index(ReminderRepository $reminderRepository)
    {
        $data = [];
        $companyCode = Auth::user()->customerEmployee->company;
        $reminders = $reminderRepository->getReminderList($companyCode);

        $defaultReminderTo = json_encode([
            'defaultReminder' => $reminderRepository->getDefaultReminder($companyCode)
        ]);

        return view('client.reminder.index', compact('reminders','defaultReminderTo'));

    }

    public function create(Request $request, VehicleRepository $vehicleRepository, ReminderRepository $reminderRepository)
    {

        $data = [];

        $reminderFor = $request->input('reminderFor');
        //dd($reminderFor);

        $data['reminderFor'] = 'custom';

        $vehicleRegNo = "";

        $vehicleId = "";
        $vehicles = "";

        if ($reminderFor) {

            $data['reminderFor'] = $reminderFor;

            if ($reminderFor == 'vehicle') {

                $vehicleRegNo = $request->input('vehicleRegHidden');

                $vehicleId = $request->input('vehicleIdHidden');

                $arr = [];

                $arr['isActiveFlag'] = 1;

                $arr['bulkFlag'] = 2;

                $arr['companyCode'] = Auth::user()->customerEmployee->company;

                $vehicles = $vehicleRepository->getVehicleInfo($arr);
            }
        }

        $defaultReminders = $reminderRepository->getDefaultReminderTo(
            Auth::user()->customerEmployee->company,
            $data['reminderFor']
        );

        return view('client.reminder.create-edit', compact('defaultReminders','reminderFor','vehicleRegNo','vehicleId','vehicles'));

    }

    public function store(Request $request, ReminderRepository $reminderRepository)
    {
        $arr = [];
        $arr['reminder_on_dt_tm'] = $request->reminderOnDate;
        $arr['heading'] = $request->reminderHeading;
        $arr['body'] = $request->reminderBody;
        $arr['repeat_every'] = (int) $request->repeatEvery;
        $arr['repeat_type'] = $request->repeatType;
        $arr['before_reminder_count'] = (int) $request->beforeReminderCount;
        $arr['before_reminder_type'] = $request->beforeReminderType;
        $arr['default_mobile_flag'] = (
            $request->defaultMobileCheck
        ) ? '1' : '0';
        $arr['default_email_flag'] = (
            $request->defaultEmailCheck
        ) ? '1' : '0';
        $arr['default_mobile_no'] = null;
        $arr['default_email'] = null;

        if (
            $arr['default_mobile_flag'] &&
            $request->defaultMobileNo
        ) {

            if ($request->defaultMobileNo) {

                $arr['default_mobile_no'] =
                    $request->defaultMobileNo;

            } else {
                return redirect()->route('client.reminder.set-reminder.create')->with('error','Default mobile number is required');
            }
        }


        if (
            $arr['default_email_flag'] &&
            $request->defaultEmail
        ) {

            if ($request->defaultEmail) {

                $arr['default_email'] =
                    $request->defaultEmail;

            } else {
                return redirect()->route('client.reminder.set-reminder.create')->with('error','Default email is required');
            }
        }


        $arr['mobile_no'] = (
            $request->moreMobileNo
        )
            ? $request->moreMobileNo
            : null;


        $arr['email'] = (
            $request->moreEmail
        )
            ? $request->moreEmail
            : null;


        $arr['reminder_for'] = $request->reminderFor;


        $arr['reminder_for_value'] = (
            $request->reminderForValue
        )
            ? $request->reminderForValue
            : null;


        $arr['reminder_type'] = (
            $request->reminderType
        )
            ? $request->reminderType
            : null;


        if (
            $arr['reminder_on_dt_tm'] &&
            $arr['body'] &&
            $arr['heading'] &&
            $arr['repeat_type'] &&
            $arr['before_reminder_type'] &&
            $arr['reminder_for']
        ) {

            $nextReminderShowDtTm = getNextReminderShowDtTm(
                $arr,
                date('Y-m-d H:i:s')
            );

            if ($nextReminderShowDtTm) {

                $arr['company'] = Auth::user()->customerEmployee->company;

                $arr['reminder_no'] = reference_no();

                $arr['next_show_dt_tm'] = $nextReminderShowDtTm;

                $arr['created_by'] = Auth::user()->user_id;

                $arr['created_dt_tm'] = Carbon::now();

                $arr['updated_by'] = Auth::user()->user_id;

                $arr['updated_dt_tm'] = Carbon::now();


                $result = $reminderRepository->addReminder($arr);

                return redirect()->route('client.reminder.set-reminder.create')->with('success','Data insert successfully');

            } else {

                return redirect()->route('client.reminder.set-reminder.create')->with('error','Next reminder date time is not valid');
            }

        } else {    

            return redirect()->route('client.reminder.set-reminder.create')->with('error','Fill up all input field');

        }
    }

    public function destroy($reminderNo, ReminderRepository $reminderRepository)
    {
        $result = $reminderRepository->removeReminder(
            $reminderNo,
            Auth::user()->customerEmployee->company,
        );

        return response()->json($result);
    }
}
