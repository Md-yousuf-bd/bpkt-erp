<?php

namespace App\Http\Controllers;

use App\Http\PigeonHelpers\otherHelper;
use App\Models\Lookup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;

class LookupController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        //
        $data['page_name']="Lookup List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Lookups','active')
        );
        return view('admin.lookups.index',$data);
    }

    public function get_index(Request $request)
    {
        $lookups=Lookup::query();

        return DataTables::eloquent($lookups)
            ->addIndexColumn()
            ->setRowId(function($lookup){
                return 'row_'.$lookup->id;
            })
            ->setRowData([
                'data-parent' => function($lookup) {
                    if(isset($lookup->parent)){
                        return $lookup->parent->name;
                    }
                    else{
                        return 'None';
                    }
                },
                'data-group' => function($lookup) {
                    if(isset($lookup->groupName)){
                        return $lookup->groupName->name;
                    }
                    else{
                        return 'None';
                    }
                },
                'data-category' => function($lookup) {
                    if(isset($lookup->category)){
                        return $lookup->category->name;
                    }
                    else{
                        return 'None';
                    }
                },
                'data-child' => function($lookup) {
                    if(isset($lookup->child)){
                        return $lookup->child->name;
                    }
                    else{
                        return 'None';
                    }
                },
                'data-child-two' => function($lookup) {
                    if(isset($lookup->childTwo)){
                        return $lookup->childTwo->name;
                    }
                    else{
                        return 'None';
                    }
                },
                'data-updated_by' => function($lookup) {
                    return $lookup->user->name;
                },
                'data-updated_at' => function($lookup) {
                    return otherHelper::change_date_format($lookup->updated_at,true,'d-M-Y h:i A');
                },
            ])
            ->addColumn('status',  function($lookup) {
                if($lookup->status==1){
                    return '<span class="badge badge-success" style="color:green">Active</span>';
                }
                else
                {
                    return '<span style="color:red" class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action',  function($lookup) {
                if(auth()->user()->can('edit-lookup')&&auth()->user()->can('delete-lookup')){
                    return '<div style="width:50%; float: left;"><a class="btn btn-xs btn-primary text-white" href="'.route('settings.lookup.edit',[$lookup->id]).'" target="_blank" ><span class="fa fa-edit">  Edit</i></a></div>
                            <div style="float:left; width:50%; text-align:center;">
                                    <form action="'.route('settings.lookup.destroy',[$lookup->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'Do you really want to delete? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                }
                elseif (auth()->user()->can('edit-lookup')){
                    return '<div style="width:100%; float: left;"><a class="btn btn-xs btn-primary text-white" href="'.route('settings.lookup.edit',[$lookup->id]).'" target="_blank" ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                elseif (auth()->user()->can('delete-lookup')){
                    return '<div style="float:left; width:100%; text-align:center;">
                                    <form action="'.route('settings.lookup.destroy',[$lookup->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'Do you really want to delete? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                }
                else{
                    return '<div style="width:100%; float: left;"></div>';
                }
            })
            ->rawColumns(['action','status'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        //
        $data['parents']=Lookup::where('parent_id','=',0)->orderBy('id','desc')->get();
        $data['page_name']="Add Lookup";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Lookups','settings.lookup.index'),
            array('Add','active')
        );
        return view('admin.lookups.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //

        $lookup=new Lookup();
        $lookup->name=$request->input('name');
        $lookup->parent_id=$request->input('parent_id');
        $lookup->priority=$request->input('priority');
        $lookup->status=$request->input('status');
        $lookup->category_id=$request->input('category_id');
        $lookup->child_id=$request->input('child_id');
        $lookup->group_id=$request->input('group_id');
        $lookup->child_id_2=$request->input('child_id_2');
        $lookup->updated_by=Auth::user()->id;
        $lookup->description=$request->input('description');
        $lookup->save();

        Logs::store(Auth::user()->name.' has Added New Lookup '.$lookup->name,'Add Lookup','success',Auth::user()->id);

        return redirect()->route('settings.lookup.index')->with('success','Lookup has been Added Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lookup  $lookup
     * @return \Illuminate\Http\Response
     */
    public function show(Lookup $lookup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lookup  $lookup
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($lookup)
    {
        //
        $data['parents']=Lookup::where('parent_id','=',0)->orderBy('id','desc')->get();
        $data['lookup']=Lookup::find($lookup);
        $data['page_name']="Edit Lookup";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Lookups','settings.lookup.index'),
            array('Edit','active')
        );

            $data['parents'] = Lookup::where('parent_id',0)->where('child_id','=',0)->where('child_id_2',0)->get();
            $data['groups'] = Lookup::where('parent_id',$data['lookup']['parent_id'])->where('group_id',0)->get();//        }else if($ref==2){
            $data['category'] = Lookup::where('group_id',$data['lookup']['group_id'])->where('child_id','=',0)->where('child_id_2',0)->get();
            $data['childs'] = Lookup::where('category_id',$data['lookup']['category_id'])->where('child_id_2','=',0)->where('child_id_2',0)->get();
            $data['childs2'] = Lookup::where('child_id_2',$data['lookup']['child_id'])->get();
//
//        }else if($ref==3){
//            $data['category'] = Lookup::where('group_id',$id)->where('child_id','=',0)->where('child_id_2',0)->get();
//        }else if($ref==4){
//            $data['category'] = Lookup::where('category_id',$id)->where('child_id','=',0)->where('child_id_2',0)->get();
//        }else if($ref==5){
//            $data['category'] = Lookup::where('child_id',$id)->where('child_id_2','=',0)->where('child_id_2',0)->get();
//        }else{
//            $data['category'] = Lookup::where('child_id_2',$id)->get();
//        }
        return view('admin.lookups.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lookup  $lookup
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $lookup)
    {
        //

        $lookup=Lookup::find($lookup);

        $lookup->name=$request->input('name');
        $lookup->parent_id=$request->input('parent_id');
        $lookup->priority=$request->input('priority');
        $lookup->group_id=$request->input('group_id');
        $lookup->category_id=$request->input('category_id');
        $lookup->status=$request->input('status');
        $lookup->updated_by=Auth::user()->id;
        $lookup->description=$request->input('description');
        $lookup->save();
        Logs::store(Auth::user()->name.' has Edited Lookup '.$lookup->name,'Edit Lookup','info',Auth::user()->id);
        return redirect()->route('settings.lookup.edit',[$lookup])->with('success','Lookup has been Edited Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lookup  $lookup
     * @return bool
     */
    public static function check_lookup_used($lookup_id){

                return false;
    }

    public function destroy($id)
    {
        //
        if (!self::check_lookup_used($id)) {
            $lookup = Lookup::find($id);
            if ($lookup->parent_id != 0) {
                $children = Lookup::where('parent_id', $id)->get();
                foreach ($children as $child) {
                    $child->parent_id = 0;
                    $child->save();
                }
                $lookup->delete();

                Logs::store(Auth::user()->name . ' has Deleted Lookup ' . $lookup->name, 'Delete Lookup', 'danger', Auth::user()->id);

                return back()->with('success', 'Lookup has been Deleted Successfully!');
            } else {
                return back()->with('error', 'You can not delete a Parent Lookup!');
            }
        }
        else{
            return back()->with('error', 'This lookup is in used. So you cannot delete it!');
        }

    }


    public static function lookup_checkup($value,$parent_id){
        if(is_numeric($value) && $value>0){
            $children=Lookup::where('parent_id',$parent_id)->where('id',$value)->count();
            if($children>0){
                return $value;
            }
            else{
                $lookup= new Lookup();
                $lookup->name=$value;
                $lookup->parent_id=$parent_id;
                $lookup->priority=0;
                $lookup->status=1;
                $lookup->updated_by=Auth::user()->id;
                $lookup->description=$value;

                $lookup->save();

                Logs::store(Auth::user()->name.' has Added New Lookup '.$lookup->name,'Add Lookup','success',Auth::user()->id);
                return $lookup->id;
            }
        }
        else{
            $duplicate=Lookup::where('parent_id',$parent_id)->where('name',$value)->get()->toArray();
            if(count($duplicate)>0){
                return $duplicate[0]->id;
            }
            else {
                $lookup = new Lookup();
                $lookup->name = $value;
                $lookup->parent_id = $parent_id;
                $lookup->priority = 0;
                $lookup->status = 1;
                $lookup->updated_by = Auth::user()->id;
                $lookup->description = $value;

                $lookup->save();

                Logs::store(Auth::user()->name . ' has Added New Lookup ' . $lookup->name, 'Add Lookup', 'success', Auth::user()->id);
                return $lookup->id;
            }
        }
    }
    public function getChild($id,$ref){

        if($ref==1){
            $data['category'] = Lookup::where('parent_id',$id)->where('child_id','=',0)->where('child_id_2',0)->get();
        }else if($ref==2){
            $data['category'] = Lookup::where('parent_id',$id)->where('group_id',0)->get();
        }else if($ref==3){
            $data['category'] = Lookup::where('group_id',$id)->where('child_id','=',0)->where('child_id_2',0)->get();
        }else if($ref==4){
            $data['category'] = Lookup::where('category_id',$id)->where('child_id','=',0)->where('child_id_2',0)->get();
        }else if($ref==5){
            $data['category'] = Lookup::where('child_id',$id)->where('child_id_2','=',0)->where('child_id_2',0)->get();
        }else{
            $data['category'] = Lookup::where('child_id_2',$id)->get();
        }

        return New JsonResponse($data);

    }
}
