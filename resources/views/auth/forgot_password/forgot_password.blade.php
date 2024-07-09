@extends('auth.index_login')
@section('title', 'Lupa Password')
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
                                        <a class="header-brand1" href="{{ url('/login') }}">
                                            <img src="{{ url('/landings') }}/img/imy.png"
                                                style="height: 100px; width: auto; margin-right: 5px; margin-top:5px;"
                                                alt="Logo Sintren">
                                            <img src="{{ url('/landings') }}/img/polindra.png"
                                                style="height: 100px; width: auto; margin-right: 10px; margin-top:5px;"
                                                alt="Logo Sintren">
                                        </a>
                                    </div>
                                    <h3>Lupa Password</h3>
                                    <form class='mb-3' action="{{ url('/lupa_password') }}" method="POST">
                                        @csrf
                                        <div class="input-group  me-auto ms-auto mb-4">
                                            <span class="input-group-addon bg-white"><i
                                                    class="fa fa-envelope text-muted-dark"></i></span>
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Email address">
                                        </div>
                                        <div class="row">
                                            <div>
                                                <button type="submit" class="btn btn-primary d-grid w-100">Log in</button>
                                            </div>
                                            <div class="col-12">
                                                <a href="{{ url('/login') }}" class="btn btn-link box-shadow-0 px-0">Sudah
                                                    Punya Akun? Login</a>
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
