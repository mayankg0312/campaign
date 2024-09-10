<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Queue\MailQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard');
    }

    public function campaign(): View
    {
        return view('create-campaign');
    }

    public function storeCampaign(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);
        $auth = Auth::user();

        $file = $request->file('csv');
        $handle = fopen($file->path(), 'r');

        $line = fgetcsv($handle);
        if(empty($line)){
            return redirect(route('campaign'))->with('error', 'CSV file is empty or not readable');
        }else{
            $i=0;
            $error = '';
            $users = [];
            $rules = [
                'email' => 'email'
            ];
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE && empty($error)) {
                if($i==0) { $i += 1; continue; }
                
                $validator = Validator::make(['email'=>$data[1]], $rules);
                if(empty($data[0])){
                    $error = "Name empty at row $i";
                }else if(empty($data[1])){
                    $error = "Email empty at row $i";
                }else if($validator->fails()){
                    $error = "Email is not in correct format at row $i";
                }else{
                    $users[] = ['username'=>$data[0], 'email'=>$data[1], 'template'=>'emails.campaign', 'campaign_name'=>$request->name];
                }
             }

             if(!empty($error)){
                return redirect(route('campaign'))->with('error', $error);
             }else{
                $users[] = ['username'=>$auth->name, 'email'=>$auth->email, 'template'=>'emails.campaign-success', 'campaign_name'=>$request->name];

                foreach($users as $user){
                    Mail::to($user['email'])->queue(new MailQueue($user));
                }
             }
        }
        return redirect(route('campaign'))->with('success', 'Campaign registered. You will receive email shortly');
    }
}
