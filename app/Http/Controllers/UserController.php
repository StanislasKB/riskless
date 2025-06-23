<?php

namespace App\Http\Controllers;

use App\Mail\UserMail;
use App\Models\AccountUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    


     public function add_user(Request $request)
    {

        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users'],
            'username' => ['required'],
            'role' => ['required'],
        ]);
        $password = ServiceController::genererMotDePasseFort();
        $user = User::create([
            'email' => $validated['email'],
            'username' => $validated['username'],
            'status' => 'ACTIVE',
            'password' => Hash::make($password),

        ]);
        $account = Auth::user()->account;
        $user->assignRole($validated['role']);
        $account_user = AccountUser::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
        ]);
       
        Mail::to($user->email)->send(new UserMail($user, $password, Auth::user()->username));
        return back()->with([
            'success' => 'L\'utilisateur a été ajouté. Il recevra un mail avec ses identifiants de connexion.',
        ]);
    }

    public function users_view()
    {
        $users = Auth::user()->account->users()->withoutRole('service_user')->get();
        return view('global_manager.page.users.index',[
            'users'=>$users
        ]);
    }
}
