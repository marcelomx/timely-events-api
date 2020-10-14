<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    const END_POINT = 'api/events';

    protected $seed = true;

    public function test_should_get_events_from_api()
    {
        $response = $this->get(static::END_POINT);

        $response
            ->assertStatus(200)
            ->assertJsonCount(15, 'data');
    }

    protected function test_should_invalidate_create_request()
    {
        $response = $this->postJson(static::END_POINT, []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title' => 'The title field is required',
                'start_date_time' => 'The start date time field is required',
                'end_date_time'   => 'The end date time field is required'
            ]);
    }

    public function test_should_invalidate_if_end_date_is_not_greather_than_start_date()
    {
        $data = Event::factory()->make()->toArray();
        $data['end_date_time'] = $data['start_date_time'];

        $response = $this->postJson(static::END_POINT, $data);
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'end_date_time' => 'The end date time must be a date after start date time.'
            ]);
    }

    public function test_should_invalidate_if_organizers_contains_an_invalid_entry()
    {
        $data = Event::factory()->make()->toArray();
        $data['organizers'] = ['invalidemail'];

        $response = $this->postJson(static::END_POINT, $data);
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'organizers' => "The organizer 'invalidemail' is not valid e-mail."
            ]);
    }

    public function test_should_register_a_new_event()
    {
        $data = Event::factory()->make()->toArray();

        $response = $this->postJson(static::END_POINT, $data);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(array_keys($data), $data)
            ->assertJsonPath('data.id', (int) Event::max('id'));
    }

    public function test_should_get_an_registered_event()
    {
        $event = Event::find(1);

        $response = $this->get(static::END_POINT . '/' . $event->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => $event->toArray()
            ]);
    }

    public function test_should_update_an_registered_event()
    {
        $event = Event::find(1);
        $data = $event->toArray();
        $data['title'] = $this->faker->text(255);
        $data['description'] = $this->faker->text();
        $data['organizers'] = [$this->faker->email]; // reseting emails

        $response = $this->postJson(static::END_POINT . '/' . $event->id, $data);

        $response
            ->assertStatus(405)
            ->assertJsonStructure(array_keys($data), $data);
    }

    public function test_should_delete_an_registered_event()
    {
        $event = Event::find(1);

        $response = $this->delete(static::END_POINT . '/' . $event->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(array_keys($event->toArray()), $event->toArray());

        $this->assertNull(Event::find(1));
    }
}
