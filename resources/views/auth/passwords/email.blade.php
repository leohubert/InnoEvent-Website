@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row h-100">
            <div class="col-12 col-md-10 mx-auto my-auto">
                <div class="card auth-card">
                    <div class="position-relative image-side ">
                        <p class=" text-white h2">{{ config('app.name') }}</p>
                        <p class="white mb-0">
                            Please use your e-mail to reset your password.
                            <br>If you are not a member, please
                            <a href="#" class="white">register</a>.
                        </p>
                    </div>
                    <div class="form-side">
                        <a href="/">
                            <span class="logo-single"></span>
                        </a>
                        <h6 class="mb-4">Forgot Password</h6>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <label class="form-group has-float-label mb-4">
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"/>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                <span>E-mail</span>
                            </label>

                            <div class="d-flex justify-content-end align-items-center">
                                <button class="btn btn-primary btn-lg btn-shadow"
                                        type="submit">{{ __('RESET') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection