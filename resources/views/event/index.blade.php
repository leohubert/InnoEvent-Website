@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @foreach($events as $event)

                <div class="col-md-5">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header">{{ $event->name }}</div>
                        <div class="card-body">
                            <h5 class="card-title">Total places : {{$event->places->where('price', '!=', -1)->count()}}</h5>
                            <h5 class="card-title">Available places : {{$event->places->where('buyer_id', '==', '')->where('price', '!=', -1)->count()}}</h5>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-success">View</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection


@section('style')

@endsection