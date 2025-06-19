@extends('auth.components.app')
@section('title')
    Changement de mot de passe
@endsection

@section('main_content')
    <div class="wrapper">
        <div class="authentication-reset-password d-flex align-items-center justify-content-center">
            <div class="row">
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="card radius-15">
                        <div class="row g-0">
                            <div class="col-lg-5">
                                <div class="card-body p-md-5">
                                    <div class="text-left">
                                        <img src="/admin/assets/images/logo-img.png" width="180" alt="">
                                    </div>
                                    <h4 class="mt-5 font-weight-bold">Créer un nouveau mot de passe</h4>
                                    <p class="text-muted">Nous avons reçu votre demande de réinitialisation du mot de passe.
                                        Veuillez saisir votre nouveau mot de passe !</p>
                                    <form action="{{ route('auth.reset_password.post') }}" method="post">
                                        @csrf
                                        <div class="mb-3 mt-5">
                                            <label class="form-label">Mot de passe</label>
                                            <input type="text" class="form-control" 
                                                name="password" required />
                                        </div>
                                        @error('password')
                                            <div class="alert alert-danger" role="alert">
                                                <div>{{ $message }}</div>
                                            </div>
                                        @enderror
                                        <div class="mb-3">
                                            <label class="form-label">Confirmation</label>
                                            <input type="text" class="form-control"
                                                name="password2" required />
                                            <input type="hidden" name="token" value="{{ request('token') }}">
                                        </div>
                                        @error('password2')
                                            <div class="alert alert-danger" role="alert">
                                                <div>{{ $message }}</div>
                                            </div>
                                        @enderror
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">Changer</button>
                                            <a href="{{ route('auth.login.view') }}" class="btn btn-light"><i
                                                    class='bx bx-arrow-back mr-1'></i>Retour</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <img src="/admin/assets/images/login-images/forgot-password-frent-img.jpg"
                                    class="card-img login-img h-100" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
