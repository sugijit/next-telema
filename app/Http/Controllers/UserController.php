<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('admin');
    // }

    public function index()
    {
        // $users = User::all();
        $user = Auth::user();
        $company_id = $user->company_id;
        $users = User::where('company_id', $company_id)->orderBy('company_id')->get();
        if($user->role == 'nl_admin') {
            $users = User::orderBy('company_id')->get();
        }
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $user = Auth::user();
        $company_id = $user->company_id;

        if ($user->role == 'nl_admin') {
            $companies = Company::all();
        } else {
            $companies = Company::where('id',$company_id)->get();
        }
        return view('users.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'company_id' => 'required|exists:companies,id', // Validate company_id
        ]);

        User::create([
            'name' => $request->name,
            'user_cd' => $request->user_cd,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pass_decrypt' => $request->password,
            'role' => $request->role,
            'company_id' => $request->company_id,
        ]);

        return redirect()->route('users.index')->with('success', 'ユーザーを追加出来ました。');
    }

    public function edit(User $user)
    {
        if(!User::isOurUser($user->id)){
            return view('dashboard');
        }
        
        $companies = Company::all();
        
        if ($user->role == 'admin') {
            $companies = Company::where('id', $user->company_id)->get();
        }

        return view('users.edit', compact('user','companies'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
    
        $user->update($validatedData);
    
        return redirect()->route('users.index')->with('success', 'ユーザー情報を更新しました。');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'ユーザーが削除されました。');
    }
}