<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Session;
use Mail;
use App\EmailTemplates;
use Illuminate\Support\Facades\Redirect;

class PasswordReset extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
    	//echo 'eeeeeeeeeeeee';die;
    	$link = url( "/admin/password/reset/" . $this->token );
		
        $sent_mail_to= $notifiable->email; 
		
		$email_template = EmailTemplates::where('slug', '=','forgot-password' )->first();
			
		$search = array("[WEBSITE_URL]");
        $replace = array($link);
			
		$message = str_replace($search, $replace, $email_template["content"]);

        $avatar = public_path()."/assets/pages/img/Fynches_Logo_Teal.png"; 
        $facebooklogo = public_path()."/assets/pages/img/facebook-logo.png"; 
        $twitterlogo = public_path()."/assets/pages/img/twitter-logo.png"; 
        $instagramlogo = public_path()."/assets/pages/img/instagram-logo.png"; 
         
        $mail_data = array('content' => $message,'toEmail' => $notifiable->email, 'subject' => 'Reset Password', 'from' => 'Fynches'
                                ,'avatar'=>$avatar,'facebooklogo'=>$facebooklogo,'twitterlogo'=>$twitterlogo,'instagramlogo'=>$instagramlogo,'link'=>$link);

        $avatar = public_path()."/assets/pages/img/Fynches_Logo_Teal.png"; 
        
        $from_email = config('constant.fromEmail');
		
        return (new MailMessage)
                    ->from($from_email,'Fynches')
					->subject( 'Reset your password' )
                    ->view('emails.mail-template',$mail_data)
                    ->line('The introduction to the notification.')
                    ->action( 'Reset Password', $link )
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
