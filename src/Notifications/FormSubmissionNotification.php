<?php

namespace Sellarix\Forms\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Sellarix\Forms\DTO\FormSubmissionDTO;
use Filament\Notifications\Notification as FilamentNotification;
use Sellarix\Forms\Models\FormField;

class FormSubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public FormSubmissionDTO $submission;

    /**
     * Create a new notification instance.
     */
    public function __construct(FormSubmissionDTO $submission)
    {
        $this->submission = $submission;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        $userName = $this->getUserName();
        $appName = config('app.name');
        $mailMessage = (new MailMessage)
            ->subject("New Form Submission")
            ->greeting('Hello Admin!')
            ->line("A  form has been submitted on {$appName}.");

        // Add user information if available
        if ($userName) {
            $mailMessage->line("**Submitted by:** {$userName}");
        }

        if ($this->submission->userId) {
            $mailMessage->line("**User ID:** {$this->submission->userId}");
        }

        $mailMessage->line("**IP Address:** {$this->submission->ipAddress}")
            ->line("**User Agent:** {$this->submission->userAgent}");

        // Add form values
        $mailMessage->line('**Form Data:**');
        foreach ($this->submission->values as $key => $value) {
            $label = FormField::find($key)->label;
            $displayValue = is_array($value) ? implode(', ', $value) : $value;
            $mailMessage->line("• **{$label}:** {$displayValue}");
        }

        // Add metadata if available
        if (!empty($this->submission->metadata)) {
            $mailMessage->line('**Additional Information:**');
            foreach ($this->submission->metadata as $key => $value) {
                $label = ucfirst(str_replace('_', ' ', $key));
                $displayValue = is_array($value) ? implode(', ', $value) : $value;
                $mailMessage->line("• **{$label}:** {$displayValue}");
            }
        }

        $mailMessage->line('Thank you for using our application!')
            ->salutation('Best regards, ' . $appName);

        return $mailMessage;
    }

    /**
     * Get the Filament notification for database storage.
     */
    public function toDatabase(User $notifiable): array
    {
        $userName = $this->getUserName();

        $title = "New Form Submission";
        $body = "A new form submission has been received" .
            ($userName ? " from {$userName}" : '') . '.';

        return FilamentNotification::make()
            ->title($title)
            ->icon('heroicon-o-document-text')
            ->body($body)
            ->info()
            ->persistent()
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->label('View Details')
                    ->url('#') // You can customize this URL to view submission details
                    ->button(),
            ])
            ->getDatabaseMessage();
    }


    /**
     * Get user name from values or metadata
     */
    private function getUserName(): ?string
    {
        return $this->submission->values['name'] ??
            $this->submission->values['full_name'] ??
            $this->submission->metadata['first_name'] ??
            null;
    }
}
