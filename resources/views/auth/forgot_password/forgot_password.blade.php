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
                                    <div id="loading" class="sk-folding-cube"
                                        style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background-color: rgba(255, 255, 255, 0.7);">
                                        <div class="sk-folding-cube"
                                            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                            <div class="sk-cube1 sk-cube"></div>
                                            <div class="sk-cube2 sk-cube"></div>
                                            <div class="sk-cube4 sk-cube"></div>
                                            <div class="sk-cube3 sk-cube"></div>
                                        </div>
                                    </div>
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
                                    <form id="forgot-password-form" class='mb-3' action="{{ url('/lupa_password') }}"
                                        method="POST">
                                        @csrf
                                        <div class="input-group  me-auto ms-auto mb-4">
                                            <span class="input-group-addon bg-white"><i
                                                    class="fa fa-envelope text-muted-dark"></i></span>
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Email address">
                                        </div>
                                        <div class="row">
                                            <div>
                                                <button type="submit" class="btn btn-primary d-grid w-100">Submit</button>
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
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#forgot-password-form');
            const loading = document.getElementById('loading');
            const submitButton = document.querySelector('button[type="submit"]');
            const cardBody = document.querySelector('.card-body');

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman form default

                // Menampilkan loading dan mendisable semua elemen dalam card
                loading.style.display = 'block';
                cardBody.style.pointerEvents = 'none'; // Mendisable semua elemen dalam card
                cardBody.style.opacity = '0.5'; // Membuat komponen menjadi abu-abu
                submitButton.disabled = true;

                // Simulasikan pengiriman form
                setTimeout(() => {
                    form.submit(); // Mengirim form setelah 2 detik
                }, 2000);
            });
        });
    </script>
@endsection
