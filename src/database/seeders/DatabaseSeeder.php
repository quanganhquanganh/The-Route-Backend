<?php

namespace Database\Seeders;

use App\Models\Roadmap;
use App\Models\User;
use App\Models\Milestone;
use App\Models\Task;
use App\Models\Like;
use App\Models\Follow;
use App\Models\Test;
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
            $random_start = $this->randomDate(new DateTime('-2 years'), new DateInterval('P2Y'));
            $milestone = Milestone::factory()->create([
                'roadmap_id' => $i + 1,
                'user_id' => $i + 1,
                'start_date' => $random_start,
                'end_date' => (clone $random_start)->add(new DateInterval('P1M')),
            ]);

            for ($k = 0; $k < 3; $k++) {
                $random = $this->randomDate($milestone->start_date, new DateInterval('P25D'));
                Task::factory()->create([
                    'milestone_id' => $milestone->id,
                    'roadmap_id' => $i + 1,
                    'user_id' => $i + 1,
                    'start_date' => $random,
                    'end_date' => (clone $random)->add(new DateInterval('P3D')),
                ]);
            }

            for ($j = 1; $j < 12; $j++) {
                $milestone = Milestone::factory()->create([
                    'roadmap_id' => $i + 1,
                    'user_id' => $i + 1,
                    'start_date' => $milestone->end_date,
                    'end_date' => (clone $milestone->end_date)->add(new DateInterval('P1M')),
                ]);

                for ($k = 0; $k < 3; $k++) {
                    $random = $this->randomDate($milestone->start_date, new DateInterval('P25D'));
                    Task::factory()->create([
                        'milestone_id' => $milestone->id,
                        'roadmap_id' => $i + 1,
                        'user_id' => $i + 1,
                        'start_date' => $random,
                        'end_date' => (clone $random)->add(new DateInterval('P3D')),
                    ]);
                }
            }
        }
        //Likes table
        for ($i = 0; $i < 10; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $random = rand(0, 1);
                if ($random == 1) {
                    Like::factory()->create([
                        'user_id' => $i + 1,
                        'roadmap_id' => $j + 1,
                    ]);
                }
            }
        }

        //Follows table
        for ($i = 0; $i < 10; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $random = rand(0, 1);
                if ($random == 1) {
                    Follow::factory()->create([
                        'user_id' => $i + 1,
                        'roadmap_id' => $j + 1,
                    ]);
                }
            }
        }
    }
}
