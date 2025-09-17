<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    // GET /admin/users
    public function index()
    {
        $users = User::with('roles')->orderBy('id', 'desc')->paginate(12);

        $counts = [
            'orders_today' => \App\Models\Order::whereDate('created_at', now())->count(),
            'kitchen_open' => \App\Models\Order::whereIn('status', ['PENDING', 'PREPARING'])->count(),
        ];

        return view('admin.users.index', compact('users', 'counts'));
    }

    // GET /admin/users/{user}/edit
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        $counts = [
            'orders_today' => \App\Models\Order::whereDate('created_at', now())->count(),
            'kitchen_open' => \App\Models\Order::whereIn('status', ['PENDING', 'PREPARING'])->count(),
        ];

        return view('admin.users.edit', compact('user', 'roles', 'userRoles', 'counts'));
    }

    // PUT /admin/users/{user}
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email',
            'roles'  => 'nullable|array',
            'roles.*'=> 'string|exists:roles,name',
        ]);

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('ok', 'تم تحديث المستخدم والأدوار');
    }

    // DELETE /admin/users/{user}
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'لا يمكنك حذف حسابك.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('ok', 'تم حذف المستخدم');
    }
}
