<?php
namespace Sellarix\Forms\Listener;


use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use Sellarix\Forms\Notifications\FormSubmissionNotification;
use Sellarix\Forms\Events\FormSubmitted;

class FormSubmittedListener
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FormSubmitted $event): void
    {
        // Get admin users
        $adminUsers = $this->getAdminUsers();

        // Send notification to all admin users
        // This will use both mail and database channels as defined in the notification
        Notification::send($adminUsers, new FormSubmissionNotification($event->submission));
    }

    /**
     * Get admin users who should receive the notification
     */
    private function getAdminUsers(): Collection
    {
        return User::all();
    }
}
