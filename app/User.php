<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Session;
use Mail;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;
use Auth;
use App\Notifications\PasswordReset;
use App\EmailTemplates;
use App\Event;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','name', 'email', 'password','token','user_status','user_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function usercreate($params,$service="") {

        // echo $service;
        //       echo "<pre>";
        //       print_r($params['emails'][0]['value']);
        //       exit;
        if($service!="")
        {
            if($service === "google"){
                //$name= explode(" ", $params['name']);
                $names = $params['name'];
                $first_name= $names['familyName'];
                $last_name= $names['givenName'];
                $email = $params['emails'][0]['value'];

            }else if($service === "facebook"){
                $name= explode(" ", $params['name']);
                $first_name= $name[0];
                $last_name= $name[1];
                $email = $params['email'];
            }
            $password = "";
            $status = "Active";
            $email_verify_code = "";
        }else{
            $first_name = $params['firstname'];
            $last_name = $params['lastname'];
            $password = bcrypt($params['password']);
            $status = $params['user_status'];
            $email = $params['email'];
            $email_verify_code = $params['rand_code'];
        }
        $user = new User;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->password = $password;
        $user->profile_image = "";
        $user->user_type = "3";


        $user->user_status = $status;
        $user->email_verify_code = $email_verify_code;

        $redirect_url= env('SITE_URL');

        $VerificationLink = $redirect_url;

        $user_name= $first_name.' '.$last_name;

        $search = array("[USERNAME]","[EMAIL]","[NEW_PASSWORD]","[WEBSITE_URL]");
        $replace = array($user_name,$user->email,$user->password, $VerificationLink);

        if($service == "")
        {
            $emailParams = array(
                'subject' => 'Fynches Signin',
                'from' => config('constant.fromEmail'),
                'to' => $params['email'],
                'email'=>$params['email'],
                'password'=>$params['password'],
                'redirect_url'=>env('SITE_URL'),
                'template'=>'new-register',
                'search' => $search,
                'replace' => $replace
            );
        }else{
            $emailParams = array(
                'subject' => 'Fynches Signin',
                'from' => config('constant.fromEmail'),
                'to' => $email,
                'email'=>$email,
                'redirect_url'=>env('SITE_URL'),
                'template'=>'new-register',
                'search' => $search,
                'replace' => $replace
            );
        }

        //pr($emailParams);die;
        $user->save();
        $result = static::SendEmail($emailParams);

        if($result == 1){
            return $user;
        }else{
            Session::flash('error_msg', 'Something went wrong!, Please try again');
        }

    }

    public static function admincreate($params){

        $user = new User;
        $user->first_name = $params['firstname'];
        $user->last_name = $params['lastname'];
        $user->email = $params['email'];
        $user->password = bcrypt($params['password']);
        $user->profile_image = "";
        $user->status = $params['status'];
        $user->user_type = "2";
        $user->email_verify_code = $params['rand_code'];


        $redirect_url= env('ADMIN_URL');

        $VerificationLink = $redirect_url;

        $user_name= $params['firstname'].' '.$params['lastname'];

        $search = array("[USERNAME]","[EMAIL]","[NEW_PASSWORD]","[WEBSITE_URL]");
        $replace = array($user_name,$user->email,$params['password'], $VerificationLink);

        $emailParams = array(
            'subject' => 'Fynches Signin',
            'from' => config('constant.fromEmail'),
            'to' => $params['email'],
            'email'=>$params['email'],
            'password'=>$params['password'],
            'redirect_url'=>env('ADMIN_URL'),
            'template'=>'new-register',
            'search' => $search,
            'replace' => $replace
        );
        $user->save();
        $result = static::SendEmail($emailParams);

        if($result == 1){
            return $user;
        }else{
            Session::flash('error_msg', 'Something went wrong!, Please try again');
        }
    }

    public static function userupdate($params) {

        $authId = Auth::user()->id;
        $edit_user_id= $params['user_id'];

        $user = User::find($params['user_id']);
        $user->first_name = $params['firstname'];
        $user->last_name = $params['lastname'];
        $user->email = $params['email'];
        $user->profile_image = $params['profile_image'];


        if($edit_user_id!=$authId)
        {
            $user->user_status = $params['user_status'];
        }

        if($authId!=$edit_user_id)
        {
            $user->user_type = '3';
        }

        $user->save();
        return $user;
    }

    public static function adminupdate($params) {


        $user = User::find($params['user_id']);
        $user->first_name = $params['firstname'];
        $user->last_name = $params['lastname'];
        $user->email = $params['email'];
        $user->profile_image = $params['profile_image'];
        $user->status = $params['status'];
        $user->user_type = '2';

        $user->save();
        return $user;
    }

    public static function updatePassword($params) {

        $user = User::find($params['user_id']);
        $user_type= $user->user_type;
        $user_name = $user->first_name.' '.$user->last_name;

        if($user_type=="2")
        {
            $redirect_url= env('ADMIN_URL');
        }else{
            $redirect_url= env('SITE_URL');
        }

        $VerificationLink = $redirect_url;

        $search = array("[USERNAME]","[EMAIL]","[NEW_PASSWORD]","[WEBSITE_URL]");
        $replace = array($user_name,$user->email,$params['password'], $VerificationLink);

        $emailParams = array(
            'subject' => 'Fynches Change Password',
            'from' => config('constant.fromEmail'),
            'to' =>$user['email'],
            'email'=>$user['email'],
            'password'=>$params['password'],
            'redirect_url'=>$redirect_url,
            'template'=>'new-password',
            'search' => $search,
            'replace' => $replace
        );

        $result = static::SendEmail($emailParams);

        if ($params['password'] != '') {
            $user->password = Hash::make($params['password']);
        }

        $user->save();
        return $user;
    }

    public static function SendEmail($params) {

        try {

            $email_template = EmailTemplates::where('slug', '=', $params["template"])->first();

            $message = str_replace($params["search"], $params["replace"], $email_template["content"]);

            $sent_mail_to= $params['to'];

            $avatar = public_path()."/assets/pages/img/Fynches_Logo_Teal.png";
            $facebooklogo = public_path()."/assets/pages/img/facebook-logo.png";
            $twitterlogo = public_path()."/assets/pages/img/twitter-logo.png";
            $instagramlogo = public_path()."/assets/pages/img/instagram-logo.png";

            $mail_data = array('content' => $message, 'toEmail' => $params["to"], 'subject' => $params["subject"], 'from' => $params['from']
            ,'avatar'=>$avatar,'facebooklogo'=>$facebooklogo,'twitterlogo'=>$twitterlogo,'instagramlogo'=>$instagramlogo);
            //pr($mail_data);die;
            $sent = Mail::send('emails.mail-template', $mail_data, function($message) use ($mail_data) {
                $message->from($mail_data['from'], 'Fynches');
                $message->to($mail_data['toEmail']);
                $message->subject($mail_data['subject']);
            });

            return true;
        } catch(Exception $e){
            echo 'Message: ' .$e->getMessage();
        }

    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $sent_mail= $this->notify(new PasswordReset($token));
    }


    function getFundingReport(){
        //return $this->belongsTo('App\FundingReport','id','user_id');
        return $this->hasOne('App\FundingReport');
    }

    function getUserEvent(){
        return $this->hasMany('App\Event');
        //return $this->belongsTo('App\Event','id','user_id');
    }
}
