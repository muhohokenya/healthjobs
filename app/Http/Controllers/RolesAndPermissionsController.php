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
    public function index(){
        return Inertia::render('iam/Index', [
            'users' => User::with('roles.permissions')->get(),
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
}
