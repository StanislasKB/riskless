@extends('auth.components.app')
@section('title')
    Réinitialisation d'adresse email
@endsection
@section('bg-class')
    bg-forgot
@endsection
@section('main_content')
    <div class="wrapper">
        <div class="authentication-forgot d-flex align-items-center justify-content-center">
            <div class="card shadow-lg forgot-box">
                <div class="card-body p-md-5">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @error('error')
                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                    @enderror
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">{{ $error }}</div>
                    @endforeach
                    <h4 class="mt-5 font-weight-bold">Réinitialisation</h4>
                    <p class="text-muted">Entrez votre nouvelle adresse email et le mot de passe pour réinitialiser votre
                        adresse email.</p>
                    <form action="{{ route('global.email_request.post') }}" method="post">
                        @csrf
                        <div class="mb-3 mt-4">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control form-control-lg radius-30"
                                placeholder="example@user.com" name="new_email" required />
                            @error('new_email')
                                <div class="alert alert-danger" role="alert">
                                    <div>{{ $message }}</div>
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 mt-4">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" class="form-control form-control-lg radius-30" name="password"
                                required />
                            @error('password')
                                <div class="alert alert-danger" role="alert">
                                    <div>{{ $message }}</div>
                                </div>
                            @enderror
                        </div>
                        <p class="mt-4 text-sm text-muted">N'oubliez pas de regarder dans les spams.</p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg radius-30">Envoyer le code</button>
                            <a href="{{ route('global.user_profile.view') }}" class="btn btn-light radius-30"><i
                                    class='bx bx-arrow-back mr-1'></i>Retour</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
