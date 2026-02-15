<?php

namespace Database\Factories;

use App\Models\Tier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tier>
 */
class TierFactory extends Factory
{
    protected $model = Tier::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->optional()->sentence(),
            'is_active' => true,
            'created_by' => null,
            'updated_by' => null,
            'level'=>$this->faker->numberBetween(1, 10),
        ];
    }
}
