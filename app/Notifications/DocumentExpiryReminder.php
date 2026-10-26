<?php

namespace App\Notifications;

use App\Models\EmployeeDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentExpiryReminder extends Notification
{
    use Queueable;

    public function __construct(
        public EmployeeDocument $document,
        public int $leadDays,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $employee = $this->document->employee;
        $settings = $employee->company->whiteLabelSettings;

        $message = (new MailMessage)
            ->subject(__('notifications.document_expiry.subject', ['employee' => $employee->full_name]))
            ->greeting(__('notifications.document_expiry.greeting'))
            ->line(__('notifications.document_expiry.line', [
                'document' => $this->document->documentType->name,
                'employee' => $employee->full_name,
                'date' => $this->document->expiry_date->format('d M Y'),
                'days' => $this->leadDays,
            ]))
            ->action(__('notifications.document_expiry.action'), route('employees.show', $employee))
            ->line(__('notifications.document_expiry.footer'));

        if ($settings) {
            $message->from($settings->mail_from_address, $settings->mail_from_name);
        }

        return $message;
    }
}
