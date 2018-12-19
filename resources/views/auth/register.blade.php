@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row h-100">
            <div class="col-12 col-md-10 mx-auto my-auto">
                <div class="card auth-card">
                    <div class="position-relative image-side ">
                        <p class=" text-white h2">{{ config('app.name') }}</p>
                        <p class="white mb-0">
                            Please use this form to register.
                            <br>If you are a member, please
                            <a href="#" class="white">login</a>.
                        </p>
                    </div>
                    <div class="form-side">
                        <a href="/">
                            <span class="logo-single"></span>
                        </a>
                        <h6 class="mb-4">Register</h6>

                        <form method="POST" action="{{ route('register') }}" id="form">
                            @csrf
                            <label class="form-group has-float-label mb-4">
                                <input id="name" type="text"
                                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                       value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                <span>Name</span>
                            </label>
                            <label class="form-group has-float-label mb-4">
                                <input id="email" type="email"
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                       value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                <span>E-mail</span>
                            </label>
                            <label class="form-group has-float-label mb-4">
                                <input id="password" type="password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                <span>Password</span>
                            </label>
                            <label class="form-group has-float-label mb-4">
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required>
                                <span>Confirm Password</span>
                            </label>
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="btn-group mr-2  mb-1" role="group">
                                    <a href="{{ route('login') }}"
                                       class="btn btn-primary btn-multiple-state" id="registerButton">
                                        <span class="label">{{ __('LOGIN') }}</span>
                                    </a>
                                    <a href="javascript:;" onclick="document.getElementById('form').submit();"
                                       class="btn btn-primary btn-multiple-state" id="successButton">
                                        <span class="label">{{ __('REGISTER') }}</span>
                                    </a>
                                </div>
                                <button type="submit" style="display: none"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

