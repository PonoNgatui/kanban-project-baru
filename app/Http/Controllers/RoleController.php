<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index()
    {
        $pageTitle = 'Role Lists';
        $roles = Role::all();

        return view('roles.index', [
            'pageTitle' => $pageTitle,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $pageTitle = 'Add Role';
        $permissions = Permission::all();
        Gate::authorize('createNewRole', Role::class);
        return view('roles.create', [
            'pageTitle' => $pageTitle,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'permissionIds' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
            ]);

            $role->permissions()->sync($request->permissionIds);

            DB::commit();

            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Role';
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        Gate::authorize('updateAnyRole', Role::class);
        return view('roles.edit', ['pageTitle' => $pageTitle, 'role' => $role, 'permissions' => $permissions]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'permissionIds' => ['required'],
        ]);
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        Gate::authorize('updateAnyRole', Role::class);
        $role->update([
            'name' => $request->name,
            $role->permissions()->sync($request->permissionIds),
           
        ]);
        return redirect()->route('roles.index');
    }

    public function delete($id)
    {
        $pageTitle = 'Delete role'; 
        $role = role::findOrFail($id); 
        Gate::authorize('deleteAnyRole', Role::class);
        return view('roles.delete', ['pageTitle' => $pageTitle, 'role' => $role]);
    
    }

    public function destroy($id)
    {
        $role = role::findorFail($id);
        Gate::authorize('deleteAnyRole', Role::class);
        $role->delete();
        return redirect()->route('roles.index');
    }
}
