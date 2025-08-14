<?php

namespace App\Mail\Reports;

use App\Models\Report;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;
    public $reportedBy;

    /**
     * Create a new message instance.
     *
     * @param  Report  $report
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
        $this->reportedBy = $report->reportedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('⚠️ New Report Created'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reports.report-created',
            with: [
                'report' => $this->report,
                'reportedBy' => $this->reportedBy,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
