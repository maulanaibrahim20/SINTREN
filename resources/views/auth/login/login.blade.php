@extends('auth.index_login')
@section('title', 'Login Page')
@section('content')
    <div class="page-content">
        <div class="container text-center text-dark">
            <div class="row">
                <div class="col-lg-4 d-block mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="text-center mb-2">
                                        <a class="header-brand1" href="{{ url('/') }}">
                                            <img src="{{ url('/landings') }}/img/imy.png"
                                                style="height: 100px; width: auto; margin-right: 5px; margin-top:5px;"
                                                alt="Logo Sintren">
                                            <img src="{{ url('/landings') }}/img/polindra.png"
                                                style="height: 100px; width: auto; margin-right: 10px; margin-top:5px;"
                                                alt="Logo Sintren">
                                            {{-- <img src="{{ url('/assets') }}/images/brand/logo-withname.png"
                                                class="header-brand-img main-logo" alt="Sparic logo"
                                                style="height: 200px; width: auto;">
                                            <img src="{{ url('/assets') }}/images/brand/logo-withname-dark.png"
                                                class="header-brand-img darklogo" alt="Sparic logo"
                                                style="height: 200px; width: auto;"> --}}
                                        </a>
                                    </div>
                                    <h3>Login</h3>
                                    <p class="text-muted">Masuk ke akun Anda</p>
                                    <form class='mb-3' action="{{ route('login.process') }}" method="POST">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <span class="input-group-addon bg-white"><i
                                                    class="fa fa-user text-dark"></i></span>
                                            <input type="text" class="form-control" placeholder="Username"
                                                name="username" value="{{ old('username') }}" id="username">
                                        </div>
                                        <div class="input-group mb-4">
                                            <span class="input-group-addon bg-white"><i
                                                    class="fa fa-unlock-alt text-dark"></i></span>
                                            <input type="password" class="form-control" placeholder="Password"
                                                name="password" id="password">
                                        </div>
                                        <div class="row">
                                            <div>
                                                <button type="submit" class="btn btn-primary d-grid w-100">Log in</button>
                                            </div>
                                            <div class="col-12">
                                                <a href="{{ url('/lupa_password') }}"
                                                    class="btn btn-link box-shadow-0 px-0">Lupa
                                                    Password?</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
