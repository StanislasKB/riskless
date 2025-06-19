@extends('auth.components.app')
@section('title')
    Connexion
@endsection
@section('bg-class')
    bg-login
@endsection
@section('main_content')
    <div class="wrapper">
        <div class="section-authentication-login d-flex align-items-center justify-content-center mt-4">
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto">
                    <div class="card radius-15 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-xl-7">
                                <div class="card-body p-5">
                                    <div class="text-center">
                                        <img src="/admin/assets/images/logo-icon.png" width="80" alt="">
                                        <h3 class="mt-4 font-weight-bold">Bon retour !</h3>
                                    </div>
                                    <div class="">
                                        <div class="form-body">
                                            @if (session('success'))
                                                <div class="alert alert-success" role="alert">
                                                    {{ session('success') }}
                                                </div>
                                            @endif

                                            @error('incorrect_information')
                                                <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                            @enderror
                                            <form class="row g-3" action="{{ route('auth.login.post') }}" method="POST">
                                                @csrf
                                                <div class="col-12">
                                                    <label for="inputEmailAddress" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="inputEmailAddress"
                                                        name="email" required>
                                                    @error('email')
                                                        <div class="alert alert-danger" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label for="inputChoosePassword" class="form-label">Mot de passe</label>
                                                    <div class="input-group" id="show_hide_password">
                                                        <input type="password" class="form-control border-end-0"
                                                            id="inputChoosePassword" name="password" required> <a href="javascript:;"
                                                            class="input-group-text bg-transparent"><i
                                                                class="bx bx-hide"></i></a>
                                                        @error('password')
                                                            <div class="alert alert-danger" role="alert">
                                                                <div>{{ $message }}</div>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="flexSwitchCheckChecked" checked="">
                                                        <label class="form-check-label" for="flexSwitchCheckChecked">Se
                                                            souvenir de moi</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 text-end"> <a
                                                        href="{{ route('auth.password_forget.view') }}">Mot de passe oublié ?</a>
                                                </div>
                                                <div class="col-12">
                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-primary"><i
                                                                class="bx bxs-lock-open"></i>Connexion</button>
                                                    </div>
                                                </div>
                                                <div class="col-12 text-center">
                                                    <p>Pas de compte ? <a href="{{ route('auth.register.view') }}">S'inscrire</a>
                                                    </p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5 bg-login-color d-flex align-items-center justify-content-center">
                                <img src="/admin/assets/images/login-images/login-frent-img.jpg" class="img-fluid"
                                    alt="...">
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
