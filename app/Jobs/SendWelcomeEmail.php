<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Start processing job for user: ' . $this->user->email);

        try {
            Mail::to($this->user->email)->send(new WelcomeEmail($this->user));
            Log::info('Email sent to: ' . $this->user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send email to: ' . $this->user->email . ' - ' . $e->getMessage());
        }

        Log::info('End processing job for user: ' . $this->user->email);
    }
}
