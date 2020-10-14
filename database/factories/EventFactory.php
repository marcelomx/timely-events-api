<?php

namespace Database\Factories;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class EventFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realText(100),
            'description' => $this->faker->text(),
            'start_date_time' => $startDateTime = $this->faker->future(),
            'end_date_time' => Carbon::create($startDateTime)->addDay(),
            'organizers' => Collection::times(
                rand(1, 5),
                function ($n) {
                    return $this->faker->email;
                }
            )->all()
        ];
    }
}
