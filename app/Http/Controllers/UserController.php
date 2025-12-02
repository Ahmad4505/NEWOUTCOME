<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id', 'DESC')->paginate(5);

        return view('users.show_users', compact('data'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.Add_user', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|same:confirm-password',
            'roles_name' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        $user = User::create($input);

        // assignRole يقبل array أو string
        $user->assignRole($request->roles_name);

        return redirect()->route('users.index')
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing a user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update user in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => "required|email|unique:users,email,$id",
            'password' => 'same:confirm-password',
            'roles'    => 'required'
        ]);

        $input = $request->all();

        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        } else {
            unset($input['password']); // بديل array_except
        }

        $user = User::findOrFail($id);
        $user->update($input);

        // حذف كل الأدوار القديمة
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')
            ->with('success', 'تم تحديث معلومات المستخدم بنجاح');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(Request $request)
    {
        User::findOrFail($request->user_id)->delete();

        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
}
