<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmMail;
use App\Mail\ResetPasswordTokenMail;
use App\Models\Account;
use App\Models\AccountUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register_view()
    {
        return view('auth.pages.register');
    }
    public function confirm_mail_view()
    {
        return view('auth.pages.confirm_mail');
    }


    public function login_view()
    {
        return view('auth.pages.login');
    }

    public function check_reset_token_view()
    {
        return view('auth.pages.check_reset_token');
    }

    public function password_forget_view()
    {
        return view('auth.pages.password_forget');
    }

    public function reset_password_view()
    {
        return view('auth.pages.reset_password');
    }
    public function first_reset_password_view()
    {
        return view('auth.pages.first_reset_password');
    }

    public function register(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'unique:users'],
            'username' => ['required'],
            'organization' => ['required'],
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'password2' => ['required', 'same:password'],
        ], [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail doit être valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'username.required' => 'Le nom d\'utilisateur est obligatoire.',
            'organization.required' => 'Le nom de l\'organisation est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
            'password.mixed' => 'Le mot de passe doit contenir au moins une lettre majuscule et une lettre minuscule.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'password.symbols' => 'Le mot de passe doit contenir au moins un caractère spécial.',
            'password.uncompromised' => 'Ce mot de passe a été trouvé dans une fuite de données, veuillez en choisir un autre.',
            'password2.required' => 'La confirmation du mot de passe est obligatoire.',
            'password2.same' => 'Les mots de passe ne correspondent pas.',
        ]);

        $confirmationToken = $this->generateUniqueConfirmationToken();
        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'status' => 'ACTIVE',
            'password' => Hash::make($request->password),
            'confirmation_token' => $confirmationToken,
            'token_expires_at' => now()->addMinutes(10),
        ]);
        $account = Account::create([
            'name' => $request->organization,
            'owner_id' => $user->id,
        ]);
        $user->assignRole('owner');

        $account_user = AccountUser::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
        ]);

        Mail::to($user->email)->send(new ConfirmMail($user));
        return redirect()->route('auth.confirm_mail.view', ['email' => $user->email]);
    }


    public function confirm_mail(Request $request)
    {
        $credentials = $request->validate([
            'code' => ['required'],

        ], [
            'code.required' => 'Le code est obligatoire.',
        ]);
        $token = (int)$request->code;
        $user = User::where('confirmation_token', $token)->firstOrFail();

        if ($user->token_expires_at && Carbon::parse($user->token_expires_at)->isPast()) {
            return redirect()->back()->withErrors(['error' => 'Le code a expiré']);
        }

        $user->confirmation_token = null;
        $user->email_verified_at = now();
        $user->save();
        return redirect(route('auth.login.view'))->with('success', 'Votre adresse e-mail a été confirmée avec succès.');
    }




    public function logout(Request $request)
    {

        Auth::logout();
        return redirect(route('auth.login.view'));
    }


    public function resend_code(Request $request)
    {
        // $credentials = $request->validate([
        //     'email' => ['required', 'email'],
        // ], [
        //     'email.required' => 'The email field is required.',
        //     'email.email' => 'Please enter a valid email address.',
        // ]);

        $user = User::where('email', request('email'))->firstOrFail();
        if ($user != null && $user->confirmation_token != null) {
            $confirmationToken = $this->generateUniqueConfirmationToken();
            $user->confirmation_token = $confirmationToken;
            $user->token_expires_at = now()->addMinutes(10);
            $user->save();
            Mail::to($user->email)->send(new ConfirmMail($user));
            return redirect()->route('auth.confirm_mail.view', [
                'email' => $user->email
            ])->with(['success' => 'Le code a été envoyé à nouveau']);
        } else {
            return redirect()->back()->withErrors(['error' => 'L\'email n\'a pas été trouvée ou a déjà été vérifiée.']);
        }
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(request()->only(['email', 'password']))) {
            $request->session()->regenerate();
            if (Auth::user()->email_verified_at && Auth::user()->status == 'ACTIVE') {

                return redirect()->intended(route('global.dashboard.view'));
            } elseif (Auth::user()->status == 'INACTIVE') {
                return back()->withErrors([
                    'incorrect_information' => 'Mot de passe ou email incorrect.',
                ]);
            } else {
                $user = User::find(Auth::user()->id);
                if ($user->roles->first()->name != 'owner') {
                    return redirect()->route('auth.first_reset_password.view');
                } else {
                    Auth::logout();
                    Mail::to($user->email)->send(new ConfirmMail($user));
                    return redirect()->route('auth.confirm_mail.view', ['email' => $user->email]);
                }
            }
        }

        return back()->withErrors([
            'incorrect_information' => 'Mot de passe ou email incorrect.',
        ]);
    }


    public function reset_password_token(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Le champ adresse email est obligatoire.',
            'email.email' => 'Cette adresse email est invalide.',
        ]);


        // Vérifier si le jeton existe dans la table "password_resets"
        $token = $this->generateUniquePasswordToken();

        $tokenData = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (!$tokenData) {
            DB::table('password_reset_tokens')->insert(
                ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
            );
        } else {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->update(['token' => $token, 'created_at' => now()]);
        }

        $user = User::where('email', $request->email)->first();
        Mail::to($user->email)->send(new ResetPasswordTokenMail($token));
        return redirect()->route('auth.check_reset_token.view', ['email' => $user->email]);
    }
    public function resend_reset_password_token(Request $request)
    {
        $token = $this->generateUniquePasswordToken();

        $tokenData = DB::table('password_reset_tokens')->where('email', request('email'))->first();
        if (!$tokenData) {
            DB::table('password_reset_tokens')->insert(
                ['email' => request('email'), 'token' => $token, 'created_at' => Carbon::now()]
            );
        } else {
            DB::table('password_reset_tokens')
                ->where('email', request('email'))
                ->update(['token' => $token, 'created_at' => now()]);
        }

        $user = User::where('email', request('email'))->first();
        Mail::to($user->email)->send(new ResetPasswordTokenMail($token));
        return redirect()->route('auth.check_reset_token.view', ['email' => $user->email])->with([
            'success' => 'Le code a été envoyé à nouveau'
        ]);
    }

    public function check_reset_password_token(Request $request)
    {
        $credentials = $request->validate([
            'code' => ['required'],

        ], [
            'code.required' => 'Le code est obligatoire.',

        ]);
        $token = (int)$request->code;

        // Vérifier si le jeton existe dans la table "password_resets"
        $tokenData = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$tokenData) {
            return back()->withErrors([
                'error' => 'Code invalide.',
            ]);
        }

        // Vérifier si le jeton a expiré
        if (Carbon::parse($tokenData->created_at)->addMinutes(10)->isPast()) {
            return back()->withErrors([
                'error' => 'Le code a expiré.',
            ]);
        }
        return redirect()->route('auth.reset_password.view', [
            'token' => $token
        ]);
    }



    public function reset_password(Request $request)
    {
        $credentials = $request->validate(
            [
                'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
                'password2' => ['required', 'same:password'],
            ],
            [
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
                'password.mixed' => 'Le mot de passe doit contenir au moins une lettre majuscule et une lettre minuscule.',
                'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
                'password.symbols' => 'Le mot de passe doit contenir au moins un caractère spécial.',
                'password.uncompromised' => 'Ce mot de passe a été trouvé dans une fuite de données, veuillez en choisir un autre.',
                'password2.required' => 'La confirmation du mot de passe est obligatoire.',
                'password2.same' => 'Les mots de passe ne correspondent pas.',
            ]);

        $tokenData = DB::table('password_reset_tokens')->where('token', $request->token)->first();


        $user = User::where('email', $tokenData->email)->firstOrFail();

        $user->password = Hash::make($request->input('password'));
        $user->save();
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        return redirect(route('auth.login.view'))->with('success', 'Votre mot de passe a été réinitialiser avec succès.');
    }
    public function first_reset_password(Request $request)
    {
       $credentials = $request->validate(
            [
                'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
                'password2' => ['required', 'same:password'],
            ],
            [
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
                'password.mixed' => 'Le mot de passe doit contenir au moins une lettre majuscule et une lettre minuscule.',
                'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
                'password.symbols' => 'Le mot de passe doit contenir au moins un caractère spécial.',
                'password.uncompromised' => 'Ce mot de passe a été trouvé dans une fuite de données, veuillez en choisir un autre.',
                'password2.required' => 'La confirmation du mot de passe est obligatoire.',
                'password2.same' => 'Les mots de passe ne correspondent pas.',
            ]);
        $user = User::findOrFail(Auth::id());

        $user->password = Hash::make($request->input('password'));
        $user->email_verified_at = now();
        $user->save();
        return redirect(route('auth.login.view'))->with('success', 'Votre mot de passe a été réinitialiser avec succès.');
    }


    public function change_password(Request $request)
    {
        // Valider les informations reçues
        $credentials = $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'new_password2' => ['required', 'same:new_password'],
        ],[
                'old_password.required' => 'Le mot de passe est obligatoire.',
                'new_password.required' => 'Le mot de passe est obligatoire.',
                'new_password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'new_password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
                'new_password.mixed' => 'Le mot de passe doit contenir au moins une lettre majuscule et une lettre minuscule.',
                'new_password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
                'new_password.symbols' => 'Le mot de passe doit contenir au moins un caractère spécial.',
                'new_password.uncompromised' => 'Ce mot de passe a été trouvé dans une fuite de données, veuillez en choisir un autre.',
                'new_password2.required' => 'La confirmation du mot de passe est obligatoire.',
                'new_password2.same' => 'Les mots de passe ne correspondent pas.',
            ]);

        $user = User::findOrFail(Auth::user()->id);

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with([
                'error' => 'L\'ancien mot de passe est incorrect',
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();
        return back()->with([
            'success' => 'Le mot de passe a été modifié avec succès.',
        ]);
    }

    private function generateUniqueConfirmationToken()
    {
        do {
            // Générer un nombre aléatoire à 4 chiffres
            $token = random_int(1000, 9999);
        } while (User::where('confirmation_token', $token)->exists());

        return $token;
    }

    public function change_user_status(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->status == 'ACTIVE') {
            $user->status = 'INACTIVE';
            $user->save();
            return back()->with('success', 'Utilisateur désactivé avec succès.');
        } elseif ($user->status == 'INACTIVE') {
            $user->status = 'ACTIVE';
            $user->save();
            return back()->with('success', 'Utilisateur activé avec succès.');
        }
    }
    public function delete_user(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = 'DELETED';
        $user->save();
        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    private function generateUniquePasswordToken()
    {
        do {
            $token = random_int(1000, 9999);
        } while (DB::table('password_reset_tokens')->where('token', $token)->exists());

        return $token;
    }
}
