<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LogController as Logs;
use App\Http\PigeonHelpers\imageHelper;
use App\Http\PigeonHelpers\otherHelper;
use App\Imports\UsersImport;
use App\Models\User_detail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

//use Illuminate\Support\Facades\DB;

class UserDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        $data['page_name']="User List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Users','active')
        );
        return view('admin.users.index',$data);
    }

    public function index_api()
    {
        //
        $data['users']=User::select('users.id','users.name','users.email','user_details.dob','user_details.gender','user_details.phone','user_details.address','user_details.picture')->join('user_details','users.id','user_details.id')->get();
        return response()->json($data);
    }

    public function get_index(Request $request)
    {
        $users=User::query()->select('users.id','users.username','users.name','users.email','users.updated_at','user_details.phone','roles.name as role_name')
            ->join('user_details','users.id','=','user_details.id')
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','model_has_roles.role_id','=','roles.id')->where('model_has_roles.model_type','App\User');

        return DataTables::eloquent($users)
            ->addIndexColumn()
            ->setRowId(function($user){
                return 'row_'.$user->id;
            })
            ->setRowData([
                'data-username' => function($user) {
                    return $user->username ?? '';
                },
                'data-email' => function($user) {
                    return $user->email ?? '';
                },
                'data-phone' => function($user) {
                    return $user->detail->phone ?? '';
                },
                'data-updated_at' => function($user) {
                    return otherHelper::change_date_format($user->detail->updated_at,true,'d-M-Y h:i A');
                },
            ])
            ->addColumn('roles', function (User $user) {
                return $user->roles->map(function($role) {
                    return ucwords(str_replace('-',' ',$role->name));
                })->implode('<br>');
            })
            ->addColumn('action',  function($user) {
                $html='<div style="width:35%; float: left;"><a class="btn btn-sm btn-info text-white" onclick="show_user_detail_modal('.$user->id.')"><span class="fa fa-info-circle">  '.Lang::get('commons/buttons.Detail').'</span></a></div>';
                if(auth()->user()->can('assign-user-permission')){
                    $html.='<div style="width:65%; float: left;"><a class="btn btn-sm btn-info text-white" onclick="show_user_permission_modal('.$user->id.')"><span class="fa fa-user-cog">  '.Lang::get('commons/buttons.Assign Permission').'</span></a></div>';
                }
                return $html;
            })
            ->addColumn('picture',  function($user) {
                $profile_image='images/defaults/user_profile_picture.png';
                if(isset($user->detail->picture)&&$user->detail->picture!=''){
                    $profile_image= 'storage/images/users/'.$user->detail->picture;
                }
                return '<img src="'.asset($profile_image).'" class="image rounded" style="width: auto; height: 50px;">';
            })
            ->rawColumns(['action','picture'])
            ->toJson();
    }

    public function get_detail_modal(Request $request){
        $user=User::find($request->input('id'));
        $data['user']=$user;
        $data['detail']=$user->detali;
        $data['role']=$user->roles()->first();
        $data['role_name']=ucwords(str_replace('-',' ',$user->roles()->first()->name));
        $data['dob']='';
        $data['age']='';
        if(isset($user->detail->dob)){
            $data['dob']= otherHelper::change_date_format($user->detail->dob);
            $data['age']= otherHelper::calculate_age($user->detail->dob);
        }
        $data['picture']=asset('images/defaults/user_profile_picture.png');
        if(isset($user->detail->picture)){
            $data['picture']= asset('storage/images/users/'.$user->detail->picture);
        }
        $auth_user=auth()->user();
        $auth_user_role=$auth_user->roles()->first();
        $role_html='';
        $data['select']=false;
        if(auth()->user()->can('edit-user-role')){
            if($auth_user->id==$user->id){
                $opt_roles=Role::where('level','>=',$auth_user_role->level)->orderBy('level','asc')->get();
            }
            else
            {
                if($auth_user_role->level<$user->roles()->first()->level&&$auth_user_role->level!=1){
                    $opt_roles=Role::where('level','>',$auth_user_role->level)->orderBy('level','asc')->get();
                }
                elseif ($auth_user_role->level<$user->roles()->first()->level&&$auth_user_role->level==1){
                    $opt_roles=Role::where('level','>=',$auth_user_role->level)->orderBy('level','asc')->get();
                }
                else
                {
                    $opt_roles=null;
                }
            }
            if(isset($opt_roles)){
                $role_html.='<select  id="role" class="select2 form-control" onchange="changeRole(\''.$user->id.'\',this.value);" style="width: 100%;">';
                foreach ($opt_roles as $opt){
                    if($data['role']->id==$opt->id){
                        $role_html.='<option value="'.$opt->id.'" selected>'.ucwords(str_replace('-',' ',$opt->name)).'</option>';
                    }
                    else{
                        $role_html.='<option value="'.$opt->id.'">'.ucwords(str_replace('-',' ',$opt->name)).'</option>';
                    }
                }
                $role_html.='</select>';
                $data['select']=true;
            }
            else
            {
                $role_html.='<label>'. $data['role_name'].'</label>';
            }

        }
        else
        {
            $role_html.='<label>'. $data['role_name'].'</label>';
        }
        $data['role_html']=$role_html;
        return response()->json($data);
    }

    public function get_permission_modal(Request $request){
        $user=User::find($request->input('id'));
        $role=$user->roles()->first();
        $data['role']=ucwords(str_replace('-',' ',$role->name));
        $role_permissions=$role->permissions()->pluck('name')->toArray();
        $role_permission_ids=$role->permissions()->pluck('id')->toArray();
        $i=0;
        foreach ($role_permissions as $permission){
            $role_permissions[$i]=ucwords(str_replace('-',' ',$permission));
            $i++;
        }
        $data['role_permissions']=implode(', ',$role_permissions);
        $data['opt_permissions']=Permission::select('id','name')->whereNotIn('id', $role_permission_ids)->get()->toArray();
        $i=0;
        foreach ($data['opt_permissions'] as $permission){
            if($user->can($permission['name'])){
                $data['opt_permissions'][$i]['selected']='selected';
            }
            else
            {
                $data['opt_permissions'][$i]['selected']='';
            }
            $data['opt_permissions'][$i]['name']=ucwords(str_replace('-',' ',$permission['name']));
            $i++;
        }
        return response()->json($data);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function change_password()
    {
        //
        $data['page_name']="Change Password";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Change Password','active')
        );
        $data['user']=User::find(Auth::user()->id);
        return view('admin.users.change_password',$data);
    }

    public function update_password(Request $request)
    {
        $user=User::find(Auth::user()->id);
        if (Hash::check($request->input('old_password'), $user->password)) {
            $user->password = Hash::make($request->input('new_password'));
            $user->decrypted_password = $request->input('new_password');
            $user->save();
            Logs::store(Auth::user()->name . ' has Changed Password ', 'Update Password', 'success', Auth::user()->id);
            Auth::logout();
            return redirect('/login');
        } else {
            return back()->with('error', 'Password has not Matched!');
        }
    }

    public function register_excel(Request $request){
        $error_count=0;
        if($request->hasFile('excel_file')){
            $data= Excel::toArray(new UsersImport,request()->file('excel_file'));
            $data=$data[0];
            foreach ($data as $row){
                if(isset($row['phone'])&&isset($row['password']))
                {
                    $phone=explode('-',$row['phone']);
                    $phone=$phone[0].$phone[1];
                    $password=$row['password'];
                    if(isset($row['username'])){
                        $username=$row['username'];
                    }
                    else
                    {
                        $username=$phone;
                    }
                    if(isset($row['name'])){
                        $name=$row['name'];
                    }
                    else
                    {
                        $name=$username;
                    }
                    $duplicate_user=User::where('username',$username)->first();
                    if(!isset($duplicate_user)){
                        if(isset($row['email'])){
                            $email=$row['email'];
                        }
                        else
                        {
                            $email='iampigeonuser'.$phone.'@gmail.com';
                        }
                        $rolename='member';
                        $user= new User();
                        $user->username=$username;
                        $user->password=Hash::make($password);
                        $user->decrypted_password=Hash::make($password);
                        $user->name=$name;
                        $user->email=$email;
                        $user->save();

                        $user_id=$user->id;

                        $user_detail=new User_detail();
                        $user_detail->id=$user_id;
                        $user_detail->phone=$phone;
                        $user_detail->address=$row['address'];
                        $user_detail->save();

                        $user->assignRole($rolename);
                    }
                    else
                    {
                        $error_count++;
                    }
                }
                else
                {
                    $error_count++;
                }
            }
            if($error_count==0)
            {
                return back()->with('success', 'All rows imported Successfully!');
            }
            elseif ($error_count>0&&$error_count<count($data))
            {
                $success_count=count($data)-$error_count;
                return back()->with('warning', $success_count.' rows inserted Successfully. But '.$error_count.' rows has failed to insert!');
            }
            else
            {
                return back()->with('error', 'All rows has failed to insert!');

            }
        }
        else
        {
            return back()->with('error', 'Excel file is required!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User_detail  $user_detail
     * @return \Illuminate\Http\Response
     */
    public function show(User_detail $user_detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User_detail  $user_detail
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile_edit()
    {
        //
        $data['user']=Auth::user();
        if(auth()->user()->can('edit-user-role')){
            $auth_user_role=$data['user']->roles()->first();
            $data['roles']=Role::where('level','>=',$auth_user_role->level)->orderBy('level','asc')->get();
        }
        else{
            $data['roles']=null;
        }
        $data['page_name']="Profile Edit";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Profile Edit','active')
        );
        return view('admin.users.profile_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User_detail  $user_detail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function profile_update(Request $request)
    {
        //

        $this->validate($request,[
            'profile_image'=>'image',
        ]);
        $user_detail=User_detail::find(Auth::user()->id);
        $user_detail->phone=$request->input('phone');
        $user_detail->dob=$request->input('dob');
        $user_detail->gender=$request->input('gender');
        $user_detail->address=$request->input('address');
        $user_detail->updated_by=Auth::user()->id;
        if($request->hasFile('profile_image')) {
            $old_image=$user_detail->picture;
            $user_detail->picture = imageHelper::image_upload($request, 'profile_image', 'images/users', strtolower(str_replace(' ','_',Auth::user()->name)), true, true,$old_image,true,400);
        }
        $user_detail->save();

        $user=User::find(Auth::user()->id);
        if($request->has('role'))
        {
            $user_roles=$user->roles;
            foreach ($user_roles as $role){
                $user->removeRole($role->name);
                Logs::store('['.$user->name.']\'s Role ['.ucwords(str_replace('-',' ',$role->name)).'] has been Removed.','Remove User Role','danger',auth()->user()->id);
            }
            $new_role=Role::find($request->input('role'));
            $user->assignRole($new_role->name);
            Logs::store('['.$user->name.'] has been assigned to new Role as ['.ucwords(str_replace('-',' ',$new_role->name)).'] User.','Assign User Role','success',auth()->user()->id);
        }
        Logs::store('['.$user->name.'] has Edited his Profile.','Edit Profile','success',auth()->user()->id);
        return redirect()->route('profile_edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User_detail  $user_detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(User_detail $user_detail)
    {
        //
    }

    public function role_change(Request $request){
        $user=User::find($request->input('user_id'));
        $user_roles=$user->roles;
        foreach ($user_roles as $role){
            $user->removeRole($role->name);
            Logs::store('['.$user->name.']\'s Role ['.ucwords(str_replace('-',' ',$role->name)).'] has been Removed.','Remove User Role','danger',auth()->user()->id);
        }
        $new_role=Role::find($request->input('role_id'));
        $user->assignRole($new_role->name);
        Logs::store('['.$user->name.'] has been assigned to new Role as ['.ucwords(str_replace('-',' ',$new_role->name)).'] User.','Assign User Role','success',auth()->user()->id);
        return true;
    }

    public function permissions_change(Request $request){
        $user=User::find($request->input('user_id'));
        $pre_permissons=$user->getDirectPermissions()->pluck('id')->toArray();
        $permissions=$request->input('permissions');
        $not_to_change=(isset($permissions))?array_intersect($pre_permissons,$permissions):$pre_permissons;
        $need_to_add=(isset($permissions))?array_diff($permissions,$not_to_change):array();
        $need_to_delete=(isset($permissions))?array_diff($pre_permissons,$not_to_change):$pre_permissons;
        foreach($need_to_delete as $permission){
            $permission=Permission::where('id',$permission)->first();
            $user->revokePermissionTo($permission->name);
            Logs::store('['.$user->name.']\'s Permission ['.ucwords(str_replace('-',' ',$permission->name)).'] has been Removed.','Remove User Permission','danger',auth()->user()->id);
        }
        $need_to_add_name=array();
        $i=0;
        foreach($need_to_add as $permission){
            $permission=Permission::where('id',$permission)->first();
            $need_to_add_name[$i]=$permission->name;
            Logs::store('['.$user->name.'] has been assigned to new Permission ['.ucwords(str_replace('-',' ',$permission->name)).'] to User.','Assign User Permission','success',auth()->user()->id);
            $i++;
        }
        $user->givePermissionTo($need_to_add_name);
        return true;
    }
}
