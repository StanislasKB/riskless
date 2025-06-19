@extends('auth.components.app')
@section('title')
    Mot de passe oublié
@endsection
@section('bg-class')
    bg-forgot
@endsection
@section('main_content')
    <div class="wrapper">
        <div class="authentication-forgot d-flex align-items-center justify-content-center">
            <div class="card shadow-lg forgot-box">
                <div class="card-body p-md-5">
                    <div class="text-center">
                        <img src="/admin/assets/images/icons/forgot-2.png" width="140" alt="" />
                    </div>
                    <h4 class="mt-5 font-weight-bold">Mot de passe oublié?</h4>
                    <p class="text-muted">Entrez votre adresse email enregistrée pour réinitialiser le mot de passe.</p>
                    <form action="{{ route('auth.password_forget.post') }}" method="post">
                        @csrf
                        <div class="mb-3 mt-4">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control form-control-lg radius-30"
                                placeholder="example@user.com" name="email" required />
                            @error('email')
                                <div class="alert alert-danger" role="alert">
                                    <div>{{ $message }}</div>
                                </div>
                            @enderror
                        </div>
                        <p class="mt-4 text-sm text-muted">N'oubliez pas de regarder dans les spams.</p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg radius-30">Envoyer le code</button>
                            <a href="{{ route('auth.login.view') }}" class="btn btn-light radius-30"><i
                                    class='bx bx-arrow-back mr-1'></i>Retour</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
