<?php
namespace App\Services;

use App\Models\Member;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function getDataForMess($idArr, $messType, $customMsgBody = null)
    {
        if ($messType == "forgetPassword") {
            $dbResponseArray = $this->getUserForgetMsg($idArr);
        } elseif ($messType == "memberBirthday" || $messType == "memberAnniversary") {
            $dbResponseArray = $this->getMemberBirthdayAnniversaryMsg($idArr);
        } elseif ($messType == "employeeBirthday" || $messType == "employeeAnniversary") {
            $dbResponseArray = $this->getEmployeeBirthdayAnniversaryMsg($idArr);
        } elseif ($messType == "memberBirthday") {
            $dbResponseArray = $this->getMemberBirthdayAnniversaryMsg($idArr);
        }elseif ($messType == "employeeAnniversary") {
            $dbResponseArray = $this->getEmployeeBirthdayAnniversaryMsg($idArr);
        }

        return $this->getFormatedMessArrayV2($dbResponseArray, $messType, $customMsgBody);
    }

    private function getEmployeeBirthdayAnniversaryMsg($employeeIdArr)
    {
        return Employee::select('primary_mobile as mob1', 'employee_name')
            ->whereIn('id', $employeeIdArr)
            ->get()
            ->toArray();
    }

    private function getMemberBirthdayAnniversaryMsg($memberIdArr)
    {
        return Member::select('primary_mobile as mob1', 'member_name')
            ->whereIn('id', $memberIdArr)
            ->get()
            ->toArray();
    }

    private function getUserForgetMsg($username)
    {
        return User::select('contact_no as mob1')
            ->where('username', $username)
            ->get()
            ->toArray();
    }

    public function getSentSms()
    {
        return DB::table('sent_sms')
            ->orderBy('created_dt_tm', 'desc')
            ->get();
    }

    public function getFormatedMessArrayV2($responsedbdata, $messType, $customMsgBody)
    {
        $msg = [];
        $messcount = 0;

        foreach ($responsedbdata as $row) {

            if (!empty($row['mob1'])) {

                $messcount++;

                $msgText = $this->getMessString($row, $messType, $customMsgBody);

                $msgLength = strlen($msgText);

                $longMsgcount = 0;
                //ata bujte hobe
                if ($msgLength > 160) {

                    $longMsgcount = (int) ($msgLength / 160);

                    if ($msgLength % 160 == 0) {
                        $longMsgcount--;
                    }
                }

                $messcount += $longMsgcount;

                $msg[] = [
                    "callerID" => "EC Niketan",
                    "toUser" => $row['mob1'],
                    "messageContent" => $msgText,
                ];
            }
        }

        return [
            'msgCount' => $messcount,
            'message' => $msg
        ];
    }

    private function getMessString($row, $messType, $customMsgBody)
    {
        $mesg = "";

        if ($messType == "memberBirthday") {
            $mesg = "Dear Life Member, On behalf of its Executive Committee, the president and General Secretary of Niketan Society wish you a Happy Birthday.";
        }

        if ($messType == "employeeBirthday") {
            $mesg = "Dear Life Member, The president and general secretary of Niketan society on behalf of its Executive Committee wishing you a Happy Birthday.";
        }

        if ($messType == "forgetPassword") {
            $mesg = "Your password has been reset. Current password is : " . $customMsgBody . " From Arrow Pos";
        }

        if ($messType == "memberAnniversary") {
             $mesg = "Dear Life Member, On behalf of its Executive Committee, the President and General Secretary of Niketan Society wish you both a delightful marriage anniversary.";
        }

        if ($messType == "employeeAnniversary") {
            $mesg = "Dear Life Member, The President and General Secretary of Niketan Society, on behalf of its Executive Committee wishing you both a very Happy Marriage Anniversary.";
        }

        if ($messType == "bulkMemberCustom" || $messType == "selectedMemberBulkCustom") {
            $mesg = $customMsgBody;
        }

        if ($messType == "bulkEmployeeCustom" || $messType == "selectedEmployeeBulkCustom") {
            $mesg = $customMsgBody;
        }

        return $mesg;
    }


    public function sendMessageV2($responsedbdata)
    {
        Log::info("Send SMS request : ".json_encode($responsedbdata));

        $crlpostdata = [
            "apikey" => config('sms.api_key'),
            "secretkey" => config('sms.secret_key'),
            "content" => $responsedbdata
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => config('sms.url'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($crlpostdata),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        Log::info("Send SMS response : ".json_encode($response));

        return $response;
    }


    public function getBalance()
    {

        $crlpostdata = [
            "apikey" => config('sms.api_key'),
            "secretkey" => config('sms.secret_key'),
            "content" => "Niketan"
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => config('sms.balance_url'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($crlpostdata),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        Log::info("Get Balance SMS response : ".json_encode($response));

        return $response;
    }

     public function sendMessage($responsedbdata)
    {

        $auth = [
            'username' => env('SMS_USERNAME'),
            'password' => env('SMS_PASSWORD')
        ];

        $data = [
            'authentication' => $auth,
            'messages' => $responsedbdata
        ];

        $crlpostdata = "JSON=" . urlencode(json_encode($data));

        $url = env('SMS_URL');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $crlpostdata);

        $result = curl_exec($ch);

        curl_close($ch);

        Log::info("SMS Response: ".$result);

        return $result;
    }
}

?>