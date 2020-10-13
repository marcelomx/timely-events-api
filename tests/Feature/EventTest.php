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

    public function testGetAll()
    {
        $response = $this->get(static::END_POINT);

        $response
            ->assertStatus(200)
            ->assertJsonCount(15, 'data');
    }

    protected function testInvalidOnCreate()
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

    public function testCreateEndDateGreatThanStartDate()
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

    public function testCreate()
    {
        $data = Event::factory()->make()->toArray();

        $response = $this->postJson(static::END_POINT, $data);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(array_keys($data), $data)
            ->assertJsonPath('data.id', (int) Event::max('id'));
    }

    public function testGet()
    {
        $event = Event::find(1);

        $response = $this->get(static::END_POINT . '/' . $event->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => $event->toArray()
            ]);
    }

    public function testUpdate()
    {
        $event = Event::find(1);
        $data = $event->toArray();
        $data['title'] = $this->faker->text(255);
        $data['description'] = $this->faker->text();

        $response = $this->postJson(static::END_POINT . '/' . $event->id, $data);

        $response
            ->assertStatus(405)
            ->assertJsonStructure(array_keys($data), $data);
    }

    public function testDelete()
    {
        $event = Event::find(1);

        $response = $this->delete(static::END_POINT . '/' . $event->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(array_keys($event->toArray()), $event->toArray());

        $this->assertNull(Event::find(1));
    }
}
