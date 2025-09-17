<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // List users
    public function index()
    {
        $users = User::with('roles')->orderBy('id','desc')->paginate(12);
        return view('users.index', compact('users'));
    }

    // Show create form
    public function create()
    {
        $roles = Role::pluck('name','name'); // manager, waiter, kitchen, bar
        return view('users.create', compact('roles'));
    }

    // Store new user
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'username' => ['required','string','max:255','unique:users,username'],
            'email'    => ['nullable','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8'],
            'role'     => ['required', Rule::in(['manager','waiter','kitchen','bar'])],
        ]);

        // If no email provided, try to use username if it looks like an email
        if (empty($data['email'])) {
            if (filter_var($data['username'], FILTER_VALIDATE_EMAIL)) {
                $data['email'] = $data['username'];
            } else {
                // fallback placeholder to satisfy NOT NULL/unique constraints
                $data['email'] = $data['username'].'@example.local';
            }
        }

        $user = User::create([
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->syncRoles([$data['role']]);

        return redirect()->route('users.index')->with('ok', 'تم إنشاء المستخدم بنجاح ✅');
    }

    // Show edit form
    public function edit(User $user)
    {
        $roles = \Spatie\Permission\Models\Role::pluck('name','name');
        $currentRole = $user->roles->pluck('name')->first();
        return view('users.edit', compact('user','roles','currentRole'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'username' => ['required','string','max:255', Rule::unique('users','username')->ignore($user->id)],
            'email'    => ['nullable','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['nullable','string','min:8'],
            'role'     => ['required', Rule::in(['manager','waiter','kitchen','bar'])],
        ]);

        if (empty($data['email'])) {
            if (filter_var($data['username'], FILTER_VALIDATE_EMAIL)) {
                $data['email'] = $data['username'];
            } else {
                $data['email'] = $data['username'].'@example.local';
            }
        }

        $payload = [
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);
        $user->syncRoles([$data['role']]);

        return redirect()->route('users.index')->with('ok', 'تم تحديث المستخدم بنجاح ✅');
    }

    // Delete user
    public function destroy(User $user)
    {
        // protect your own account if you want:
        // if (auth()->id() === $user->id) { return back()->withErrors('لا يمكنك حذف حسابك'); }
        $user->delete();
        return back()->with('ok','تم حذف المستخدم 🗑️');
    }
}
