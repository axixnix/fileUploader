<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Verification;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $verification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Verification $verification)
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
        return $this->subject("Reset your password")->view('mails.resetpassword',[
            'user'=> $this->user,
            'verification'=>$this->verification
        ]);
    }
}
