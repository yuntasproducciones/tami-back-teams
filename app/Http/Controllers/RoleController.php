<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::all();
        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'required|string|max:255',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return response()->json([
            'success' => true,
            'data' => $role
        ], 201);
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function show($id){
        $role = Role::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */

     public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
            'guard_name' => 'required|string|max:255',
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */


     public function destroy($id){
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    /**
     * Assign a role to a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

     public function assignRoleToUser(Request $request, $userId){
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($userId);
        $role = Role::findOrFail($request->role_id);

        $user->assignRole($role);

        return response()->json([
            'success' => true,
            'message' => 'Rol asignado al usuario exitosamente'
        ]);
    }

    /**
     * Eliminar rol de usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

     public function removeRoleFromUser(Request $request, $userId){
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($userId);
        $role = Role::findOrFail($request->role_id);

        $user->removeRole($role);

        return response()->json([
            'success' => true,
            'message' => 'Rol eliminado del usuario exitosamente'
        ]);
    }

    public function getUserRoles($userId)
    {
        $user = User::with('roles')->findOrFail($userId); 

        $roles = $user->roles->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
            ];
        });

        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'user_name' => $user->name, 
            'roles' => $roles,
            'message' => 'Roles del usuario obtenidos exitosamente'
        ]);
    }

}
