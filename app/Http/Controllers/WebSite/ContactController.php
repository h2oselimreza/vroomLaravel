<?php

namespace App\Http\Controllers\WebSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(){
        return view("website.contact.contact-form");
    }

    public function contactUsMailSend(Request $request)
    {
        $postData['name'] = trim($request->name);
        $postData['customerMail'] = trim($request->fromMail);
        $postData['mailHeading'] = trim($request->mailHeading);
        $postData['mailBody'] = trim($request->mailBody);
        if ($postData['name'] && $postData['customerMail'] && $postData['mailHeading'] && $postData['mailBody']) {

            $mailArr['toMail'] = "rifatsakib230@gmail.com"; // society mail
            $mailArr['customerMail'] = $postData['customerMail'];
            $mailArr['mailHeading'] = $postData['mailHeading'];
            $mailArr['mailBody'] = $postData['mailBody'];

            Mail::send([], [], function ($message) use ($mailArr) {
                $message->to($mailArr['toMail'])
                        ->from($mailArr['customerMail'])
                        ->subject($mailArr['mailHeading'])
                        ->html($mailArr['mailBody']);
            });

            return back()->with('success', 'Mail has been sent');
        }

        return back();
    }
}
