<?php

namespace App\Http\Controllers;

use App\Mail\UserMail;
use App\Models\AccountUser;
use App\Models\NotificationType;
use App\Models\User;
use App\Models\UserNotificationSetting;
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


    public function user_profile()
    {
        $notifications =NotificationType::all();
        return view('global_manager.page.user_profil.index',[
            'notifications'=>$notifications
        ]);
    }

    public function update_username(Request $request)
    {
         $credentials = $request->validate([
            'username' => ['required'],

        ]);
        $user=User::findOrFail(Auth::id());
        $user->username= $credentials['username'];
        $user->save();
        return back()->with([
            'success' => 'Le nom d\'utilisateur a été modifié.',
        ]);
    }

     public function update_profil_img(Request $request)
    {
        $credentials = $request->validate([
            'profil_img' => ['file', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);
        $user = User::findOrFail(Auth::user()->id);
        $updateData = [];
        if ($request->hasFile('profil_img')) {
            $path = $request->file('profil_img')->store('profil_img','public');
            $updateData['profile_url'] = $path;
        }
       
        $user->update($updateData);
        return back()->with('success', 'Image de profil mis à jour avec succès');
    }


    public function updateNotificationPreferences(Request $request)
{
    $user = Auth::user(); 

    $selected = $request->input('notifications', []); 

    $allTypes = NotificationType::all();

    foreach ($allTypes as $type) {
        $enabled = array_key_exists($type->code, $selected);
        UserNotificationSetting::updateOrCreate(
            [
                'user_id' => $user->id,
                'notification_type_id' => $type->id
            ],
            [
                'enabled' => $enabled
            ]
        );
    }

    return redirect()->back()->with('success', 'Préférences mises à jour.');
}
}
