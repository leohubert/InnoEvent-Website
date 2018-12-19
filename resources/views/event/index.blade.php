@extends('layouts.app')

@section('content')
    <div class="container">
        <br>
        <div class="row">
            @foreach($events as $event)


                <div class="col-xs-6 col-lg-3 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <p class="list-item-heading mb-4">{{ $event->name }}</p>
                            <p class="list-item-heading text-small mb-4">Total places : {{$event->places->where('price', '!=', -1)->count()}}</p>
                            <p class="list-item-heading text-small mb-4">Available places : {{$event->places->where('buyer_id', '==', '')->where('price', '!=', -1)->count()}}</p>
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary btn-xs mb-1">View Tickets</a>
                            <footer>
                                <p class="text-muted text-small mb-0 font-weight-light">{{ $event->created_at->format('d.m.Y') }}</p>
                            </footer>
                        </div>
                        <div class="position-relative">
                            <img class="card-img-top" src="/img/stade{{ rand(0, 3) }}.jpg" alt="stade">
                            @if ($event->id > 1)
                                <span class="badge badge-pill badge-theme-1 position-absolute badge-top-left">NEW</span>
                            @endif

                            @if ($event->id > 9)
                                <span class="badge badge-pill badge-secondary position-absolute badge-top-left-2">TRENDING</span>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection


@section('style')

@endsection