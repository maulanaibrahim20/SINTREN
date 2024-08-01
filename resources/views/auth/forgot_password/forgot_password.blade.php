@extends('auth.index_login')
@section('title', 'Lupa Password')
@section('css')
    <style>
        #submit-button {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #button-text {
            margin-right: 10px;
        }

        .spinner4 {
            display: flex;
            position: absolute;
            right: 50%;
            transform: translateX(50%);
        }

        .bounce1,
        .bounce2,
        .bounce3 {
            width: 18px;
            height: 18px;
            background-color: #fff;
            border-radius: 100%;
            display: inline-block;
            animation: bouncedelay 1.4s infinite ease-in-out both;
        }

        .bounce1 {
            animation-delay: -0.32s;
        }

        .bounce2 {
            animation-delay: -0.16s;
        }

        @keyframes bouncedelay {

            0%,
            80%,
            100% {
                transform: scale(0);
            }

            40% {
                transform: scale(1);
            }
        }
    </style>
@endsection
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
                                                <button type="submit" class="btn btn-primary d-grid w-100"
                                                    id="submit-button">
                                                    <span id="button-text">Submit</span>
                                                    <span id="button-spinner" class="spinner4" style="display: none;">
                                                        <div class="bounce1"></div>
                                                        <div class="bounce2"></div>
                                                        <div class="bounce3"></div>
                                                    </span>
                                                </button>
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
            const submitButton = document.getElementById('submit-button');
            const buttonText = document.getElementById('button-text');
            const buttonSpinner = document.getElementById('button-spinner');

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman form default

                // Mengubah teks tombol menjadi loader
                buttonText.style.display = 'none';
                buttonSpinner.style.display = 'flex';
                submitButton.disabled = true;

                // Simulasikan pengiriman form
                setTimeout(() => {
                    form.submit(); // Mengirim form setelah 2 detik
                }, 2000);
            });
        });
    </script>
@endsection
