<?php

namespace Database\Factories\Library;

use App\Models\Library\LibraryPlan;
use App\Models\Library\LibrarySubscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LibrarySubscription>
 */
class LibrarySubscriptionFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = now();

        return [
            'user_id' => User::factory(),
            'library_plan_id' => LibraryPlan::factory(),
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addDays(30),
            'downloads_used' => 0,
            'status' => 'active',
        ];
    }
}
