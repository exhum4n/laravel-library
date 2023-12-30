<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Jobs;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class SendEmailJob extends AbstractJob
{
    protected Mailable $mail;

    protected string $email;

    public function __construct(string $email, Mailable $mail)
    {
        $this->email = $email;
        $this->mail = $mail;
    }

    public function handle(): void
    {
        Mail::to($this->email)->send($this->mail);
    }
}
