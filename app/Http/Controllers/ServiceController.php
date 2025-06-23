<?php

namespace App\Http\Controllers;

use App\Mail\UserMail;
use App\Models\AccountUser;
use App\Models\Service;
use App\Models\ServiceUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ServiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $account = $user->account;
        $services = $account->services()->get();
        $users = $account->users()->role('service_user')->get();
        return view('global_manager.page.service.index', [
            'services' => $services,
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
        ]);

        $user = Auth::user();

        if (!$user->hasRole('admin') && !$user->hasRole('owner')) {
            return redirect()->back()->withErrors(['error' => 'Vous n\'avez pas le rôle nécessaire pour créer un service']);
        }
        $account = $user->account;
        $service = Service::create([
            'account_id' => $account->id,
            'name'       => $validated['name'],
        ]);

        return back()->with([
            'success' => 'Le service a bien été créé au sein du compte.',
        ]);
    }

    public function add_service_user(Request $request)
    {

        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users'],
            'username' => ['required'],
            'service' => ['required', 'array'],
            'service.*' => ['string'],
            'permission' => ['required', 'array'],
            'permission.*' => ['string', 'exists:permissions,name'],
        ]);
        $password = self::genererMotDePasseFort();
        $user = User::create([
            'email' => $validated['email'],
            'username' => $validated['username'],
            'status' => 'ACTIVE',
            'password' => Hash::make($password),

        ]);
        $account = Auth::user()->account;
        $user->assignRole('service_user');
        $account_user = AccountUser::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
        ]);
        foreach ($validated['permission'] as $permission) {
            $user->givePermissionTo($permission);
        }
        foreach ($validated['service'] as $service) {
            $service_user = ServiceUser::create([
                'user_id' => $user->id,
                'service_id' => (int) $service,
            ]);
        }
        Mail::to($user->email)->send(new UserMail($user, $password, Auth::user()->username));
        return back()->with([
            'success' => 'L\'utilisateur a été ajouté. Il recevra un mail avec ses identifiants de connexion.',
        ]);
    }

    public function updateUserPermissions(Request $request, $id)
    {
        $validated = $request->validate([
            'permission' => 'array|required',
            'permission.*' => 'string|exists:permissions,name',
        ]);
        $user = User::findOrFail($id);
        $permissions = $validated['permission'];

        $user->syncPermissions($permissions);

        return back()->with([
            'success' => 'Permissions mises à jour avec succès.',
        ]);
    }

    

    public static function genererMotDePasseFort($longueur = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        return substr(str_shuffle($characters), 0, $longueur);
    }
}
