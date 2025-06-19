@extends('auth.components.app')
@section('title')
    Inscription
@endsection
@section('bg-class')
    bg-register
@endsection
@section('main_content')
    <div class="section-authentication-register d-flex align-items-center justify-content-center">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card radius-15 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-xl-7">
                            <div class="card-body p-md-5">
                                <div class="text-center">
                                    <img src="/admin/assets/images/logo-icon.png" width="80" alt="">
                                    <h3 class="mt-4 font-weight-bold">Créer un compte</h3>
                                </div>
                                <div class="">
                                    <div class="form-body">
                                        <form class="row g-3" action="{{ route('auth.register.post') }}" method="POST">
											@csrf
                                            <div class="col-sm-6">
                                                <label for="inputFirstName" class="form-label">Nom d'utilisateur</label>
                                                <input type="text" class="form-control" id="inputFirstName"
                                                    placeholder="John Doe" name="username" required>
                                                @error('username')
                                                    <div class="alert alert-danger" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-sm-6">
                                                <label for="inputLastName" class="form-label">Organisation</label>
                                                <input type="text" class="form-control" id="inputLastName"
                                                    placeholder="FYXOP" name="organization" required>
                                                @error('organization')
                                                    <div class="alert alert-danger" role="alert">
                                                        <div>{{ $message }}</div>
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="inputEmailAddress"
                                                    placeholder="example@user.com" name="email" required>
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
                                                        id="inputChoosePassword"
                                                        placeholder="Enter Password" name="password" required> <a href="javascript:;"
                                                        class="input-group-text bg-transparent"><i
                                                            class="bx bx-hide"></i></a>
                                                    @error('password')
                                                        <div class="alert alert-danger" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword2" class="form-label">Confirmation</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control border-end-0"
                                                        id="inputChoosePassword2"
                                                        placeholder="Enter Password" name="password2" required> <a
                                                        href="javascript:;" class="input-group-text bg-transparent"><i
                                                            class="bx bx-hide"></i></a>
                                                    @error('password2')
                                                        <div class="alert alert-danger" role="alert">
                                                            <div>{{ $message }}</div>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckChecked" required>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">J'ai lu et
                                                        accepte les Termes et Conditions</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="bx bx-user me-1"></i>Créer le compte</button>
                                                </div>
                                            </div>
											<div class="col-12 text-center">
                                                    <p>Vous avez déjà un compte ? <a href="{{ route('auth.login.view') }}">Se connecter</a>
                                                    </p>
                                                </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-xl-5 bg-login-color d-flex align-items-center justify-content-center">
                            <img src="/admin/assets/images/login-images/register-frent-img.jpg" class="img-fluid"
                                alt="...">
                        </div>
                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>
    </div>
@endsection
