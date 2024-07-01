<?php

namespace App\Http;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        info('schedule running');

        // Schedule a task to run every day at 12:00 PM
        $schedule->call(function () {
            $users = User::all();
            foreach ($users as $user) {
                try {
                    $completionPercentage = $this->calculateCompletionRate($user);

                    $client = new Client();
                    $body = "Your profile is {$completionPercentage}% complete. Please update your profile to enjoy full benefits.";

                    $url = 'https://api.adalo.com/notifications';
                    $headers = [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer 5ckiny17el2vymy81icxgnsbu',
                    ];

                    $data = [
                        'appId' => '0fb25ec4-853d-487d-a48e-bb871341619a',
                        'audience' => ['id' => $user->id],
                        'notification' => [
                            'titleText' => 'Profile Completion Status',
                            'bodyText' => $body,
                        ],
                    ];

                    $response = $client->post($url, [
                        'headers' => $headers,
                        'json' => $data,
                    ]);
                    info($response->getBody()->getContents());

                } catch (Exception $exception) {
                    info($exception->getMessage());
                }
            }
        })->dailyAt('12:00');
    }

    /**
     * Calculate profile completion rate.
     */
    private function calculateCompletionRate(User $user): int
    {
        $fields = ['name', 'email', 'dob', 'city', 'country', 'phone', 'bio', 'profession'];
        $completedFields = 0;

        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }

        return ($completedFields / count($fields)) * 100;
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
