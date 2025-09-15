<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use JetBrains\PhpStorm\NoReturn;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RolesAndPermissionsController extends Controller
{
    public function index()
    {
        $users = User::with('roles.permissions')
            ->leftJoin('sessions', function ($join) {
                $join->on('users.id', '=', 'sessions.user_id')
                    ->whereRaw('sessions.last_activity = (
                     SELECT MAX(last_activity)
                     FROM sessions s2
                     WHERE s2.user_id = users.id
                 )');
            })
            ->select([
                'users.*',
                'sessions.last_activity'
            ])
            ->get()
            ->map(function ($user) {
                $user->last_seen = $user->last_activity
                    ? \Carbon\Carbon::createFromTimestamp($user->last_activity)->diffForHumans()
                    : 'Never';

                $user->is_online = $user->last_activity && \Carbon\Carbon::createFromTimestamp($user->last_activity)->diffInMinutes(now()) < 60;

                // Remove the raw timestamp from the response
                unset($user->last_activity);

                return $user;
            });

        return Inertia::render('iam/Index', [
            'users' => $users,
        ]);
    }

    public function map(){
        return Inertia::render('iam/Map', [
            'permissions' => Permission::query()->orderBy('name')->get(),
            'roles' => Role::query()->with('permissions')->get(),
            'user' =>User::with('roles.permissions')->get(),
        ]);
    }

    public function roles()
    {
        return Inertia::render('iam/Roles', [
            'roles' => Role::query()->with('permissions')->get(),
        ]);

    }

    public function createRole()
    {
//        return Inertia::render('iam/roles/Create');

    }

    public function create(){
        return Inertia::render('iam/CreateUser',[
            'roles'=> Role::query()->orderBy('name')->with('permissions')->get(),
        ]);
    }


    private function sendWelcomeEmail(User $user){
        Log::info("Send email later to the user");
    }


    #[NoReturn]
    public function store(Request $request){
        $plain_password = Str::password(14);
        $password = Hash::make($plain_password);
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:facilities,email|max:255',
            'roles' => 'required',
        ]);

        Log::info($plain_password);

        $user = User::query()->create([
            'name' => $request->name,
            'password' => $password,
            'email' => $request->email,
        ]);

        $user->givePermissionTo('access-dashboard');
        $user->assignRole($request->roles);


        self::sendWelcomeEmail($user);

    }

    #[NoReturn]
    public function update(Request $request){
        $permission_ids = $request->permissions;
        $role_id = $request->role_id;

        $role = Role::query()->find($role_id);

        $permissions = Permission::query()->whereIn('id', $permission_ids)->pluck('name')->toArray();


        $role->syncPermissions($permissions);
       return redirect()->route('iam.roles');
    }
}
