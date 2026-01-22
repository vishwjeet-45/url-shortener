<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Company,User,Role};

class CompanyController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        if($user->hasRole('SuperAdmin')){
            $companies = Company::paginate(10);
            return view('dashboard',compact('companies'));
        }elseif($user->hasRole('Admin')){
            $users = $user->company->users()->paginate(10);
            return view('Admin.dashboard',compact('users'));
        }elseif($user->hasRole('Member')){
            return view('Member.dashboard');
        }else{
            return view('Member.dashboard');    
        }
    }

    public function index()
    {
        if(!auth()->user()->hasRole('SuperAdmin'))
        {
            abort(403,'Unauthorized Action');
        }
        $companies = Company::paginate(10);
        return view('dashboard',compact('companies'));
    }

    public function create()
    {
        if(!auth()->user()->hasRole('SuperAdmin'))
        {
            abort(403,'Unauthorized Action');
        }
        return view('company_create');
    }

    public function store(Request $request)
    {
         if(!auth()->user()->hasRole('SuperAdmin'))
        {
            abort(403,'Unauthorized Action');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
        ]);
        DB::beginTransaction();
        try {
            $company = Company::create($validated);
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('password'), 
                'company_id' => $company->id,
                'role_id' => Role::where('name', 'Admin')->first()->id,
            ]);
            DB::commit();
            // dd($request->all(), $company, $company->id, Role::where('name', 'Admin')->first()->id,$validated);
            return redirect()->route('companies.index')->with('success', 'Company created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create company and admin user.']);
        }
    }
}
