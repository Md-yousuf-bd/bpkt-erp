<?php

namespace App\Http\Controllers;

use App\Http\PigeonHelpers\otherHelper;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\LogController as Logs;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function role_index()
    {
        //
        $data['page_name']="Role List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Roles','active')
        );
        return view('admin.roles.index',$data);
    }

    public function get_role_index(Request $request)
    {
        $roles=Role::query();

        return DataTables::eloquent($roles)
            ->addIndexColumn()
            ->setRowId(function($role){
                return 'row_'.$role->id;
            })
            ->setRowData([
                'data-role' => function($role) {
                    return ucwords(str_replace('-',' ',$role->name));
                },
                'data-created_at' => function($role) {
                    return otherHelper::change_date_format($role->created_at,true,'d-M-Y h:i A');
                },
                'data-updated_at' => function($role) {
                    return otherHelper::change_date_format($role->updated_at,true,'d-M-Y h:i A');
                },
            ])
            ->addColumn('action',  function($role) {
                if(auth()->user()->can('edit-role')){
                    return '<div style="width:100%; float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('settings.role.edit',[$role->id]).'" target="_blank" ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                else{
                    return '<div style="width:100%; float: left;"></div>';
                }
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function permission_index()
    {
        //
        $data['page_name']="Permission List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Permissions','active')
        );
        return view('admin.permissions.index',$data);
    }

    public function get_permission_index(Request $request)
    {
        $permissions=Permission::query();

        return DataTables::eloquent($permissions)
            ->addIndexColumn()
            ->setRowId(function($permission){
                return 'row_'.$permission->id;
            })
            ->setRowData([
                'data-permission' => function($permission) {
                    return ucwords(str_replace('-',' ',$permission->name));
                },
                'data-created_at' => function($permission) {
                    return otherHelper::change_date_format($permission->created_at,true,'d-M-Y h:i A');
                },
                'data-updated_at' => function($permission) {
                    return otherHelper::change_date_format($permission->updated_at,true,'d-M-Y h:i A');
                },
            ])
            ->addColumn('action',  function($permission) {
                if(auth()->user()->can('edit-permission')){
                    return '<div style="width:100%; float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('settings.permission.edit',[$permission->id]).'" target="_blank" ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                else{
                    return '<div style="width:100%; float: left;"></div>';
                }
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function permission_create()
    {
        //
        $data['page_name']="Add Permission";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Permissions','settings.permission.index'),
            array('Add','active')
        );
        $data['roles']=Role::all();
        return view('admin.permissions.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function permission_store(Request $request)
    {
        //
        $name=strtolower(str_replace(' ','-', $request->input('name')));
        $duplicate=Permission::where('name',$name)->get()->toArray();
        if(count($duplicate)>0){
            return redirect()->back()->with('error','Permission Name has been used before.');
        }
        $guard_name=$request->input('guard_name');
        $roles=$request->input('roles');
        $permission = Permission::create(['name' => $name,'guard_name' => $guard_name]);
        if(isset($roles)&&count($roles)>0){
            foreach ($roles as $role){
                $permission->assignRole($role);
                $role=Role::find($role);
                Logs::store(ucwords(str_replace('-',' ',$role->name)).' has been assigned permission '.ucwords(str_replace('-',' ',$permission->name)).'.','Assign Permission to Role','success',auth()->user()->id);
            }
        }
        Logs::store(ucwords(str_replace('-',' ',$request->input('name'))).' Permission has been added successfully.','Add Permission','success',auth()->user()->id);
        return redirect()->route('settings.permission.index')->with('success','Permission has been added successfully.');
    }

    public function role_create()
    {
        //
        $data['page_name']="Add Role";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Roles','settings.role.index'),
            array('Add','active')
        );
        $data['permissions']=Permission::all();
        return view('admin.roles.create',$data);
    }

    public function role_store(Request $request)
    {
        //
        $name=strtolower(str_replace(' ','-', $request->input('name')));
        $duplicate=Role::where('name',$name)->get()->toArray();
        if(count($duplicate)>0){
            return redirect()->back()->with('error','Role Name has been used before.');
        }
        $guard_name=$request->input('guard_name');
        $level=$request->input('level');
        $permissions=$request->input('permissions');
        $role = Role::create(['name' => $name,'guard_name' => $guard_name,'level'=>$level]);
        if(isset($permissions)&&count($permissions)>0){
            foreach ($permissions as $permission){
                $permission=Permission::find($permission);
                $permission->assignRole($role);
                Logs::store(ucwords(str_replace('-',' ',$role->name)).' has been assigned permission '.ucwords(str_replace('-',' ',$permission->name)).'.','Assign Permission to Role','success',auth()->user()->id);
            }
        }
        Logs::store(ucwords(str_replace('-',' ',$request->input('name'))).' Role has been added successfully.','Add Role','success',auth()->user()->id);
        return redirect()->route('settings.role.index')->with('success','Role has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function role_edit(Role $role)
    {
        //
        $data['page_name']="Edit Role";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Roles','settings.role.index'),
            array('Edit','active')
        );
        $data['role']=$role;
        $data['permissions']=Permission::where('guard_name',$role->guard_name)->get();
        return view('admin.roles.edit',$data);
    }

    public function permission_edit(Permission $permission)
    {
        //
        $data['page_name']="Edit Permission";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Permissions','settings.permission.index'),
            array('Edit','active')
        );
        $data['permission']=$permission;
        $data['roles']=Role::where('guard_name',$permission->guard_name)->get();
        return view('admin.permissions.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function role_update(Request $request, Role $role)
    {
        //
        $guard_name=$request->input('guard_name');
        $level=$request->input('level');
        $pre_permissions=$role->permissions()->pluck('id')->toArray();
        $permissions=$request->input('permissions');
        $not_to_change=(isset($permissions))?array_intersect($pre_permissions, $permissions):$pre_permissions;
        $need_to_add=(isset($permissions))?array_diff($permissions, $not_to_change):array();
        $need_to_delete=(isset($permissions))?array_diff($pre_permissions, $not_to_change):$pre_permissions;
        $role->guard_name=$guard_name;
        $role->level=$level;
        $role->save();
        foreach ($need_to_delete as $permission_id){
            $permission=Permission::find($permission_id);
            $role->revokePermissionTo($permission);
            Logs::store(ucwords(str_replace('-',' ',$permission->name)).' has been removed from role '.ucwords(str_replace('-',' ',$role->name)).'.','Remove Permission from Role','danger',auth()->user()->id);
        }
        foreach ($need_to_add as $permission_id){
            $permission=Permission::find($permission_id);
            $role->givePermissionTo($permission);
            Logs::store(ucwords(str_replace('-',' ',$role->name)).' has been assigned permission '.ucwords(str_replace('-',' ',$permission->name)).'.','Assign Permission to Role','success',auth()->user()->id);
        }
        Logs::store(ucwords(str_replace('-',' ',$role->name)).' Role has been Edited successfully.','Edit Role','success',auth()->user()->id);
        return redirect()->back()->with('success','Role has been edited successfully.');
    }

    public function permission_update(Request $request, Permission $permission)
    {
        //
        $guard_name=$request->input('guard_name');
        $level=$request->input('level');
        $pre_roles=$permission->roles()->pluck('id')->toArray();
        $roles=$request->input('roles');
        $not_to_change=(isset($roles))?array_intersect($pre_roles, $roles):$pre_roles;
        $need_to_add=(isset($roles))?array_diff($roles, $not_to_change):array();
        $need_to_delete=(isset($roles))?array_diff($pre_roles, $not_to_change):$pre_roles;
        $permission->guard_name=$guard_name;
        $permission->save();
        foreach ($need_to_delete as $role_id){
            $role=Role::find($role_id);
            $role->revokePermissionTo($permission);
            Logs::store(ucwords(str_replace('-',' ',$permission->name)).' has been removed from role '.ucwords(str_replace('-',' ',$role->name)).'.','Remove Permission from Role','danger',auth()->user()->id);
        }
        foreach ($need_to_add as $role_id){
            $role=Role::find($role_id);
            $role->givePermissionTo($permission);
            Logs::store(ucwords(str_replace('-',' ',$role->name)).' has been assigned permission '.ucwords(str_replace('-',' ',$permission->name)).'.','Assign Permission to Role','success',auth()->user()->id);
        }
        Logs::store($permission->name.' Permission has been Edited successfully.','Edit Role','success',auth()->user()->id);
        return redirect()->back()->with('success','Permission has been edited successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
