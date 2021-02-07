<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordNew extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $resetLink;

    /**
     * Create a new message instance.
     *
     * @param string $resetLink
     */
    public function __construct(string $resetLink)
    {
        $this->resetLink = $resetLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.password.new', [
                'link' => $this->resetLink,
            ])
            ->subject(__('Willkommen im Timelapse Systems Kundenportal!'));
    }
}
