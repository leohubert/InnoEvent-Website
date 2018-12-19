@extends('layouts.app')

@section('content')
    <div class="container">
        <br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card bg-dark text-light">

                    <div class="card-body">
                        <h5 class="mb-4">Import Event</h5>

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 ">
                                    <input type="file" name="event" placeholder="InnoEvent Project Fine JSon"
                                           class="form-control btn btn-primary btn-upload" accept=".json">
                                </div>


                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-file">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
