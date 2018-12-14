@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Event List</div>
                    <a href="{{ route('events.create') }}" class="btn btn-primary">Create New Event</a>
                    <div class="card-body">
                        @foreach($events as $event)
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-danger"> Event :{{ $event->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
