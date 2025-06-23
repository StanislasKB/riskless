<?php

namespace App\Http\Controllers;

use App\Mail\EmailChangeCode;
use App\Mail\EmailChangedNotification;
use App\Models\EmailChange;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EmailChangeController extends Controller
{
    public function request_view()
    {
        return view('auth.pages.reset_email_request');
    }

    public function email_change_check_view()
    {
        return view('auth.pages.reset_email_check_code');
    }
    public function requestEmailChange(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email',
            'password'  => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['error' => 'Mot de passe incorrect']);
        }
        $code = random_int(100000, 999999);
        EmailChange::where('user_id', $user->id)->delete();
        $token = Str::random(64);

        EmailChange::create([
            'user_id'    => $user->id,
            'new_email'  => $request->new_email,
            'token'      => hash('sha256', $token),
            'code'       => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
        Mail::to($request->new_email)->send(new EmailChangeCode($code,$user));

       return redirect()->route('global.email_change_check.view', ['email' => $request->new_email]);
    }
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

       $user=User::findOrFail(Auth::id());

        $emailChange = EmailChange::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$emailChange) {
            return back()->withErrors(['error' => 'Code invalide ou expiré.']);
        }
        $old_email=$user->email;
        $user->email = $emailChange->new_email;
        $user->save();
        // Suppression de la demande
        $emailChange->delete();
    
        // Envoie de la notification de sécurité à l’ancienne adresse
        Mail::to($old_email)->send(new EmailChangedNotification());

        return redirect()->route('global.user_profile.view')->with([
            'success' => 'Votre adresse email a été modifiée avec succès.'
        ]);
    }

    public function resendCode()
    {
        $user = Auth::user();

        $emailChange = EmailChange::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$emailChange) {
            return back()->withErrors(['email' => 'Aucune demande en cours.']);
        }

        $code = random_int(100000, 999999);
        $emailChange->update([
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);
        Mail::to($emailChange->new_email)->send(new EmailChangeCode($code,$user));
         return redirect()->route('global.email_change_check.view', ['email' => $emailChange->new_email])->with([
            'success' => 'Un nouveau code de vérification a été envoyé.'
        ]);
    }
}
