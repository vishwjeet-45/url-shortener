<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Role};

class UserController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        if(!auth()->user()->hasRole('Admin'))
        {
            abort(403,'Unauthorized Action');
        }
        $roles = Role::select('id','name')->whereIn('name',['Member','Sales','Manager','Admin'])->get();
        return view('Admin.user_create',compact('roles'));
    }

    public function store(Request $request)
    {
        if(!auth()->user()->hasRole('Admin'))
        {
            abort(403,'Unauthorized Action');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt('password'),
            'role_id' => $validated['role_id'],
            'company_id' => $request->company_id,
        ]);

        return redirect()->route('dashboard')->with('success', 'User invited successfully.');
    }
}
