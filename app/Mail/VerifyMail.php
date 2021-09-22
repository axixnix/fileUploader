<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Verification;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $verification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,Verification $verification)
    {
        //
        $this->user =$user;
        $this->verification = $verification;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('welcome to our service, please continue the verification process')->view('mails.verify',[
            'user'=> $this->user,
            'verification'=>$this->verification
        ]);
    }
}
