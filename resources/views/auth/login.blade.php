@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row h-100">
            <div class="col-12 col-md-10 mx-auto my-auto">
                <div class="card auth-card">
                    <div class="position-relative image-side ">

                        <p class=" text-white h2">{{ config('app.name') }}</p>

                        <p class="white mb-0">
                            Please use your credentials to login.
                            <br>If you are not a member, please
                            <a href="#" class="white">register</a>.
                        </p>
                    </div>
                    <div class="form-side">
                        <a href="/">
                            <span class="logo-single"></span>
                        </a>
                        <h6 class="mb-4">{{ __('Login') }}</h6>
                        <form method="POST" action="{{ route('login') }}" id="form">
                            @csrf

                            <label class="form-group has-float-label mb-4">
                                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       value="{{ old('email') }}" name="email" id="email" required autofocus/>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                <span>{{ __('E-Mail') }}</span>
                            </label>

                            <label class="form-group has-float-label mb-4">
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       type="password" name="password" id="password" placeholder="" required/>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                <span>{{ __('Password') }}</span>
                            </label>
                            <div class="d-flex justify-content-between align-items-center">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                @endif
                                <div class="btn-group mr-2  mb-1" role="group">
                                    <a href="{{ route('register') }}"
                                       class="btn btn-primary btn-multiple-state" id="registerButton">
                                        <span class="label">{{ __('REGISTER') }}</span>
                                    </a>
                                    <a href="javascript:;" onclick="document.getElementById('form').submit();"
                                       class="btn btn-primary btn-multiple-state" id="successButton">
                                        <span class="label">{{ __('LOGIN') }}</span>
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