<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEmployeeAdded extends Notification implements ShouldQueue
{
    use Queueable;

    protected $employee;

    /**
     * Create a new notification instance.
     */
    public function __construct(Employee $employee)
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        $this->employee = $employee;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Employee Added')
            ->greeting("Hello {$notifiable->name}")
            ->line("A new employee ({$this->employee->full_name}) has been added to your company.")
            ->line("Email: " . ($this->employee->email ?? '-'))
            ->line("Phone: " . ($this->employee->phone ?? '-'))
            ->action('View Company', url('/companies'))
            ->line('Thank you.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
