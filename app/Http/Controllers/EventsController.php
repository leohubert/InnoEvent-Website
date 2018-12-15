<?php

namespace App\Http\Controllers;

use App\Event;
use App\Offer;
use App\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;

class EventsController extends Controller
{
    /**
     * EventsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::all();
        return view('event.index', ['events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('event.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'event' => 'required'
        ]);

        $path = Storage::putFile('events', $request->file('event'));

        $fileGet = Storage::get($path);

        $json = json_decode($fileGet);

        $room = $json->room;
        $places = $json->pricing->_places;
        $offers = $json->pricing->_offers;


        $event = Event::create([
            'name' => $room->_name,
            'file' => $path
        ]);

        foreach ($offers as $offer) {
            Offer::create([
                'name' => $offer->_name,
                'event_id' => $event->id
            ]);

        }

        foreach ($places as $place) {
            if (substr_count($place->_id, '|') == 2) {
                $createdPlace = Place::create([
                    'place_id' => $place->_id,
                    'color' => $place->_color,
                    'price' => $place->_price,
                    'event_id' => $event->id
                ]);

                foreach ($place->_offers as $placeOffer) {
                    $offer = $event->offers->where('name', $placeOffer->_name)->first();
                    $createdPlace->offers()->syncWithoutDetaching($offer->id);
                }
            }
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $event = Event::findOrFail($id);


        if ($request->wantsJson()) {

            $fileGet = Storage::get($event->file);
            $json = json_decode($fileGet);

            $response = array();

            $response['room'] = $json->room;
            $response['event'] = $event;
            $response['places'] = $event->places;
            return response()->json($response);

        }
        return view('event.show', ['event' => $event]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
