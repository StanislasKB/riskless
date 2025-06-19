@extends('auth.components.app')
@section('title')
    Réinitialisation de mot de passe
@endsection
@section('bg-class')
    bg-forgot
@endsection
@section('main_content')
    <div class="wrapper">
        <div class="authentication-forgot d-flex align-items-center justify-content-center">
            <div class="card shadow-lg forgot-box">
                <div class="card-body p-md-5">

                    <h4 class="mt-5 font-weight-bold">Entrez le code de réinitialisation</h4>
                    <p class="text-muted">Nous vous avons envoyé un code sur {{ request('email') }} </p>
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @error('error')
                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                    @enderror

                    <form action="{{ route('auth.check_reset_token.post') }}" method="post">
                        @csrf
                        <div class="mb-3 mt-4">
                            <input type="number" min="0" class="form-control form-control-lg radius-30"
                                name="code" required />
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg radius-30">Continuer</button>
                    </form>
                    <div class="mt-3 d-flex justify-content-start align-items-end">
                        <p class="mb-0">Vous n'avez pas reçu l'e-mail ?</p>
                        <a href="{{ route('auth.resend_reset_password_token', ['email' => request('email')]) }}"
                            class="link-primary ms-2">Renvoyer le code</a>
                    </div>
                    <a href="{{ route('auth.login.view') }}" class="btn btn-light radius-30"><i
                            class='bx bx-arrow-back mr-1'></i>Retour</a>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
