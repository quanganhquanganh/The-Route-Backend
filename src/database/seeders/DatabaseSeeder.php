<?php

namespace Database\Seeders;

use App\Models\Roadmap;
use App\Models\User;
use App\Models\Task;
use App\Models\Todo;
use Illuminate\Database\Seeder;
use DateInterval;
use DateTime;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    
    private function randomDate($start_date, $interval)
    {
        $end_date = clone $start_date;
        $end_date->add($interval);

        // Convert to timetamps
        $min = $start_date->getTimestamp();
        $max = $end_date->getTimestamp();

        // Generate random number using above bounds
        $val = rand($min, $max);

        // Convert back to desired date format
        return DateTime::createFromFormat('U', $val);
    }

    public function run()
    {
        Roadmap::factory(10)->create();

        for ($i = 0; $i < 10; $i++) {
            $task = Task::factory()->create([
                'roadmap_id' => $i + 1,
                'user_id' => $i + 1,
            ]);

            for ($k = 0; $k < 3; $k++) {
                $random = $this->randomDate($task->start_date, new DateInterval('P25D'));
                Todo::factory()->create([
                    'task_id' => $task->id,
                    'roadmap_id' => $i + 1,
                    'user_id' => $i + 1,
                    'start_date' => $random,
                    'end_date' => (clone $random)->add(new DateInterval('P3D')),
                ]);
            }

            for ($j = 1; $j < 12; $j++) {
                $task = Task::factory()->create([
                    'roadmap_id' => $i + 1,
                    'user_id' => $i + 1,
                    'start_date' => $task->start_date->add(new DateInterval('P1M')),
                ]);

                for ($k = 0; $k < 3; $k++) {
                    $random = $this->randomDate($task->start_date, new DateInterval('P25D'));
                    Todo::factory()->create([
                        'task_id' => $task->id,
                        'roadmap_id' => $i + 1,
                        'user_id' => $i + 1,
                        'start_date' => $random,
                        'end_date' => (clone $random)->add(new DateInterval('P3D')),
                    ]);
                }
            }
        }
    }
}
