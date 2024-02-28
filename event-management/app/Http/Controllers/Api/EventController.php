<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewEventRequest;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show');
        $this->middleware('throttle:api')->only(['store', 'update', 'destroy']);
        $this->authorizeResource(Event::class, 'event');
    }
    public function index()
    {
        $query = $this->loadRelationships(Event::query());
        return EventResource::collection($query->latest()->paginate());
    }

    public function store(NewEventRequest $request)
    {
        $data = $request->validated();
        $event = Event::create([
            ...$data,
            'user_id' => $request->user()->id
        ]);
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        // if (Gate::denies('update-event', $event)) {
        //     abort(403, 'You are not authorized to Update this Event');
        // }
        // $this->authorize('update-event', $event);

        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ])
        );
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // if (Gate::denies('update-event', $event)) {
        //     abort(403, 'You are not authorized to Update this Event');
        // }
        $event->delete();

        return response()->json(status: 204);
    }
}
