<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Application $application
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Application Received - ' . $this->application->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.application-received',
            with: [
                'applicantName' => $this->application->user->name,
                'jobTitle' => $this->application->job->title,
                'url' => route('recruiter.applications.show', $this->application->id),
            ],
        );
    }
}
