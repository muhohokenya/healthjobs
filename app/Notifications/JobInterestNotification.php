<?php

namespace App\Notifications;

use App\Models\HealthJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Job;

class JobInterestNotification extends Notification
{
    use Queueable;

    protected $candidate;
    protected $job;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $candidate, HealthJob $job)
    {
        $this->candidate = $candidate;
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('A new candidate has shown interest in your job posting.')
            ->action('View Job', url('/jobs/'.$this->job->uuid))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Job Interest',
            'message' => "{$this->candidate->name} has shown interest in your job posting: {$this->job->title}",
            'type' => 'job_interest',
            'user' => [
                'id' => $this->candidate->id,
                'name' => $this->candidate->name,
                'email' => $this->candidate->email,
                'licence_number' => $this->candidate->licence_number,
                'licence_number_expiry' => $this->candidate->licence_number_expiry,
                'licence_status' => $this->candidate->licence_status,
                'avatar' => $this->candidate->avatar ?? null,
            ],
            'job' => [
                'id' => $this->job->id,
                'uuid' => $this->job->uuid,
                'title' => $this->job->title,
                'job_type' => $this->job->job_type,
                'salary_min' => $this->job->salary_min,
                'salary_max' => $this->job->salary_max,
                'experience_level' => $this->job->experience_level,
            ],
            'action_url' => route('health-jobs.show', $this->job->uuid),
            'created_at' => now()->toISOString(),
        ];
    }
}
