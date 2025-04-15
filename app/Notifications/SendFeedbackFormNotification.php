<?php

namespace App\Notifications;

use App\Models\FeedbackForm;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendFeedbackFormNotification extends Notification
{
    use Queueable;

    private FeedbackForm $feedbackForm;

    /**
     * Create a new notification instance.
     */
    public function __construct(FeedbackForm $feedbackForm)
    {
        $this->feedbackForm = $feedbackForm;
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
                    ->subject('Заполнена форма обратной связи')
                    ->line('Ф.И.О.: ' . $this->feedbackForm->full_name)
                    ->line('Почта: ' . $this->feedbackForm->email)
                    ->line('Телефон: ' . $this->feedbackForm->phone)
                    ->line('Файл: ' . !is_null($this->feedbackForm->file)
                        ? url('/storage/' . $this->feedbackForm->file)
                        : null
                    )
                    ->line('Сообщение:')
                    ->line($this->feedbackForm->message);
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
