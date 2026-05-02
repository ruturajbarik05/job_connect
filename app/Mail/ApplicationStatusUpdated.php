<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Application $application,
        public string $newStatus
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Status Updated - ' . $this->application->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.application-status-updated',
            with: [
                'jobTitle' => $this->application->job->title,
                'status' => $this->newStatus,
                'url' => route('jobseeker.applications.show', $this->application->id),
            ],
        );
    }
}
