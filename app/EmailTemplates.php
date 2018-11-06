<?php

namespace App;

use DB;
use Mail;
use Illuminate\Database\Eloquent\Model;

Class EmailTemplates extends Model {

    protected $table = 'email_templates';

    /*
     * Added By: Devang Mavani
     * Modified Date: 04/06/2018
     * Reason: get template record
     */

    function scopeGetTemplates($id = "") {
        $page = $this->input->get('sEcho');
        $iDisplayLength = $this->input->get('iDisplayLength');

        if ($page != 1 && $page != 0) {
            $start = $page * $iDisplayLength;
            $end = $start + $iDisplayLength;
        } else {
            $start = 1;
            $end = 10;
        }

        $user = email_templates::find($id);

        return $user;
    }

    /*
     * Added By: Devang Mavani
     * Reason: Add template to database
     */

    public static function emailTemplate_insert($data) {

        // Inserting in Table(students) of Database(college)
        //DB::table('email_templates')->save($data);
        $email_templates = new EmailTemplates;
        $email_templates->subject = $data['subject'];
        $slug = EmailTemplates::slugify($data['subject']);
        $email_templates->slug = $slug;
        $email_templates->content = $data['content'];
		$email_templates->status = $data['status'];
        $email_templates->save();
        return $email_templates;
    }

    /*
     * Added By: Devang Mavani
     * Reason: update template to database
     */

    public static function emailTemplate_update($params) {

        $email_templates = EmailTemplates::find($params['update_id']);

        $email_templates->subject = $params['subject'];
        $email_templates->content = $params['content'];
		$email_templates->status = $params['status'];
        $email_templates->save();

        return $email_templates;
    }

    /*
     * Added By: Devang Mavani
     * Modified Date: 04/06/2018
     * Reason: Convert email template title to slug, create slug
     */

    static function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text)) {
            return 'n-a';
        }


        //DB::enableQueryLog(); 
        $query = DB::table('email_templates')->select('slug')->where('slug', $text)->get();

        $tot = DB::table('email_templates')->select('slug')->where('slug', $text)->count();

        // if record more then one then add integer
        if ($tot > 0) {
            $text = $text . "-" . $tot;
        }

        return $text;
    }

    function scopeGetTemplate($slug) {
        
        $query = DB::table('email_templates')->select('id,subject as title,slug,content')->where('slug', $slug)->get();
        return $query;
    }

   

    /*
     * Added By: Devang
     * 
     * Reason: send mail
     */

    public static function Sendemail($params) {
        $email_template = EmailTemplates::where('slug', '=', $params["template"])->first();
        $params["subject"] = $email_template["subject"];
        $message = str_replace($params["search"], $params["replace"], $email_template["content"]);
        $mail_data = array('content' => $message, 'toEmail' => $params["to"], 'subject' => $email_template["subject"]);

        //Admin
        $sent = Mail::send('emails.mail-template', $mail_data, function($message) use ($mail_data) {
                    $message->to($mail_data['toEmail']);
                    $message->subject($mail_data['subject']);
                });

        if ($sent == true) {
            return true;
        } else {
            show_error($this->email->print_debugger());
            return false;
        }
    }

}

?>
