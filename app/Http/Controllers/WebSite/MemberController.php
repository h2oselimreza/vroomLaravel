<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MemberController extends Controller
{
    public function applyMembership(Request $request){
        return view("website.member.apply-membership");
    }
    public function applyForMembershipMailSend(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'mobile'   => 'required|digits_between:10,15',
            'fromMail' => 'required|email|max:100',
            'address'  => 'required|string|max:500',
        ]);

        $mailData = [
            'name'  => $validated['name'],
            'mobile'=> $validated['mobile'],
            'email' => $validated['fromMail'],
            'address'=> $validated['address'],
        ];

        Mail::send([], [], function ($message) use ($mailData) {
            $message->to('rifatsakib230@gmail.com')  // Society email
                    ->subject('Apply For Membership')
                    ->from($mailData['email'], $mailData['name'])
                    ->setBody(
                        "Name: {$mailData['name']}<br>" .
                        "Mobile: {$mailData['mobile']}<br>" .
                        "Email: {$mailData['email']}<br>" .
                        "Address: {$mailData['address']}",
                        'text/html'
                    );
        });

        // Redirect back with success message
        return redirect()->back()->with('success', 'Mail has been sent successfully!');
    }
    public function applyForCarSticker(){
         return view("website.member.apply-car-sticker");
    }

    public function applyForCarStickerMailSend(Request $request)
    {
        // ✅ Validation
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'member_id'   => 'required|string|max:50',
            'mobile_no'   => 'required|digits_between:10,15',
            'email'       => 'required|email|max:100',
            'reg_no'      => 'required|string|max:50',
            'brand_name'  => 'required|string|max:50',
            'model'       => 'required|string|max:50',
            'license_no'  => 'required|string|max:50',
        ]);

        // Prepare mail data
        $mailData = [
            'name'       => $validated['name'],
            'member_id'  => $validated['member_id'],
            'mobile_no'  => $validated['mobile_no'],
            'email'      => $validated['email'],
            'reg_no'     => $validated['reg_no'],
            'brand_name' => $validated['brand_name'],
            'model'      => $validated['model'],
            'license_no' => $validated['license_no'],
        ];

        // Send Mail
        Mail::send([], [], function ($message) use ($mailData) {
            $message->to('rifatsakib230@gmail.com') // society mail
            ->subject('Apply For Car Sticker')
            ->from('no-reply@example.com', $mailData['name'])
            ->html(
                "Name: {$mailData['name']}<br>" .
                "Member ID: {$mailData['member_id']}<br>" .
                "Mobile No: {$mailData['mobile_no']}<br>" .
                "Email: {$mailData['email']}<br>" .
                "Vehicle Reg. No: {$mailData['reg_no']}<br>" .
                "Brand Name: {$mailData['brand_name']}<br>" .
                "Model: {$mailData['model']}<br>" .
                "License No: {$mailData['license_no']}"
            );
        });

        // Redirect back with success message
        return redirect()->back()->with('success', 'Mail has been sent successfully!');
    }

    public function lifeMember(){
        $members = Member::with(['block', 'road'])
            ->where('is_active', 1)
            ->get();
        $pageHeading = 'Life Member';
        $donarMember = 0;
        return view('website.member.life-member', compact('members','pageHeading','donarMember'));
    }

    public function donarMember(){
        $members = Member::with(['block', 'road'])
            ->where('is_active', 1)
            ->get();
        $pageHeading = 'Life Member';
        $donarMember = 1;
        return view('website.member.donar-member', compact('members','pageHeading','donarMember'));
    }

}
