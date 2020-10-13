<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\Resources\Json\JsonResource;

class EventController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return JsonResource::collection(Event::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\EventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $event = Event::create($request->all());

        return new JsonResource($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return new JsonResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\EventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, Event $event)
    {
        $event->update($request->all());

        return new JsonResource($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return new JsonResource($event);
    }
}
