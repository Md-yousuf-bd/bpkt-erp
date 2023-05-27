<?php

namespace App\Http\Controllers;

use App\Http\PigeonHelpers\otherHelper;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        //
        $data['page_name']="Location List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Locations','active')
        );
        return view('admin.locations.index',$data);
    }

    public function get_index(Request $request)
    {
        $locations=Location::query();

        return DataTables::eloquent($locations)
            ->addIndexColumn()
            ->setRowId(function($location){
                return 'row_'.$location->id;
            })
            ->setRowData([
                'data-type' => function($location) {
                    if($location->type=='thana'){
                        return 'থানা';
                    }
                    elseif ($location->type=='zone')
                    {
                        return 'সার্কেল/জোন';
                    }
                    elseif ($location->type=='district')
                    {
                        return 'বিভাগ/জেলা';
                    }
                    elseif ($location->type=='division')
                    {
                        return 'রেঞ্জ/মেট্রো';
                    }
                },
                'data-parent' => function($location) {
                    if($location->parent_id >0) {
                        if (isset($location->parent)) {
                            return $location->parent->name;
                        } else {
                            return 'বাংলাদেশ পুলিশ';
                        }
                    }
                    else{
                        return 'বাংলাদেশ পুলিশ';
                    }
                },
                'data-parent_type' => function($location) {
                    if(isset($location->parent)){
                        if($location->parent->type=='thana'){
                            return 'থানা';
                        }
                        elseif ($location->parent->type=='zone')
                        {
                            return 'সার্কেল/জোন';
                        }
                        elseif ($location->parent->type=='district')
                        {
                            return 'বিভাগ/জেলা';
                        }
                        elseif ($location->parent->type=='division')
                        {
                            return 'রেঞ্জ/মেট্রো';
                        }
                    }
                    else{
                        return 'বাংলাদেশ পুলিশ';
                    }
                },
                'data-updated_by' => function($location) {
                    return $location->update_user->name;
                },
                'data-updated_at' => function($location) {
                    return otherHelper::change_date_format($location->updated_at,true,'d-M-Y h:i A');
                },
            ])
            ->addColumn('action',  function($location) {
                if(auth()->user()->can('edit-location')&&auth()->user()->can('delete-location')){
                    return '<div style="width:50%; float: left;"><a class="btn btn-xs btn-primary text-white" href="'.route('settings.location.edit',[$location->id]).'" target="_blank" ><span class="fa fa-edit">  Edit</i></a></div>
                            <div style="float:left; width:50%; text-align:center;">
                                    <form action="'.route('settings.location.destroy',[$location->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                }
                elseif (auth()->user()->can('edit-location')){
                    return '<div style="width:100%; float: left;"><a class="btn btn-xs btn-primary text-white" href="'.route('settings.location.edit',[$location->id]).'" target="_blank" ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                elseif (auth()->user()->can('delete-location')){
                    return '<div style="float:left; width:100%; text-align:center;">
                                    <form action="'.route('settings.location.destroy',[$location->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        //
        $data['parents']=self::get_parent_by_type();
        $data['page_name']="Add Location";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Locations','settings.location.index'),
            array('Add','active')
        );
        return view('admin.locations.create',$data);
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

        $names=$request->input('name');
        $names=explode('=',$names);
        foreach ($names as $name){

            if(self::check_double($request)==true) {
                $location = new Location();
                $location->name = $name;
                $location->display_name = $name;
                $location->parent_id = $request->input('parent_id');
                $location->type = $request->input('type');
                $location->created_by = Auth::user()->id;
                $location->updated_by = Auth::user()->id;

                $location->save();

                Logs::store(Auth::user()->name . ' has Added New ' . $location->type . ' ' . $location->name, 'Add Location', 'success', Auth::user()->id);
            }
            else
            {
                return redirect()->route('location.create')->with('error', $name.' is already exists!');
            }
        }
        if($request->input('submit')=='Rapid Submit'){
            return redirect()->route('settings.location.create')->with('success','Location has been Added Successfully!');
        }
        else
        {
            return redirect()->route('settings.location.index')->with('success','Location has been Added Successfully!');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Location $location)
    {
        //
        $data['location']=Location::find($location->id);
        if($location->parent_id!=0)
        {
            $parent=Location::find($location->parent_id);
            if(isset($parent)){
                $parent_type=$parent->type;
                $data['parent_type']=$parent_type;
                $data['parents']=self::get_parent_by_type($parent_type);
            }
            else
            {
                $parent_type='';
                $data['parent_type']=$parent_type;
                $data['parents']=array();
            }
        }
        else
        {
            $parent_type='';
            $data['parent_type']=$parent_type;
            $data['parents']=array();
        }
        $data['page_name']="Edit Location";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Settings','active'),
            array('Locations','settings.location.index'),
            array('Edit','active')
        );
        return view('admin.locations.edit',$data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Location $location)
    {
        //
        if(self::check_double($request)==true)
        {
            $location=Location::find($location->id);

            $old_name=$location->name;

            $location->name=$request->input('name');
            $location->display_name=$request->input('display_name');
            $location->english_name=$request->input('english_name');
            $location->parent_id=$request->input('parent_id');
            $location->type=$request->input('type');
            $location->updated_by=Auth::user()->id;

            $location->save();
//            if($old_name!=$request->input('name')) {
//                $this->change_report_name($old_name, $request->input('name'), $request->input('type'));
//            }
            Logs::store(Auth::user()->name.' has Edited '.$location->type. ' '.$location->name,'Edit Location','info',Auth::user()->id);
            return redirect()->route('settings.location.edit',[$location])->with('success','Location has been Edited Successfully!');
        }
        else
        {
            return redirect()->route('settings.location.edit',[$location])->with('error','This Location is already exists!');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return bool
     */

    private function check_location_used($location_id){
        if(Case_info::where('thana_no',$location_id)->count()>0){
            return true;
        }
        else{
            return false;
        }
    }

    public function destroy(Location $location)
    {
        //
        if (!self::check_location_used($location->id)) {
            $children = Location::where('parent_id', $location->id)->get();

            foreach ($children as $child) {
                $c = Location::find($child->id);
                $c->parent_id = 0;
                $c->updated_by = \Auth::user()->id;
                $c->save();
            }
            $location = Location::find($location->id);
            $old_name = $location->name;
            $type = $location->type;
            $location->delete();

            Logs::store(Auth::user()->name . ' has Deleted Location ' . $location->name, 'Delete Location', 'danger', Auth::user()->id);

            return back()->with('success', 'Location has been Deleted Successfully!');
        }
        else{
            return back()->with('error', 'এই স্থানের অধীনে মামলা যুক্ত রয়েছে। তাই ডিলিট করা সম্ভব নয়!');
        }
    }

    public function check_double(Request $request)
    {
        if($request->input('_method')=='PATCH'){
            $location=Location::where('name',$request->input('name'))->where('type',$request->input('type'))->where('id','!=',$request->input('location_id'))->get()->toArray();
        }
        else{
            $location=Location::where('name',$request->input('name'))->where('type',$request->input('type'))->get()->toArray();
        }
        if(count($location)<=0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function get_display_name($name,$type){
        $location=Location::where('name',$name)->where('type',$type)->first();
        if(isset($location)){
            return $location->display_name;
        }
        else{
            return '';
        }
    }

    public function get_parent_by_type($type='zone',$json=false)
    {
        if($type=='zone')
        {
            $parents=Location::where('type','zone')->orderBy('id','desc')->get();
        }
        else if($type=='district')
        {
            $parents=Location::where('type','district')->orderBy('id','desc')->get();
        }
        else if($type=='division')
        {
            $parents=Location::where('type','division')->orderBy('id','desc')->get();
        }
        else
        {
            $parents=array();
        }


        if($json)
        {
            $data=array();
            $i=0;
            foreach($parents as $p)
            {
                $data[$i]=array();
                $data[$i][0]=$p->id;
                $data[$i][1]=$p->name;
                $data[$i][2]=$p->parent_id;
                $data[$i][3]=$p->type;
                $data[$i][4]=$p->created_by;
                $data[$i][5]=$p->updated_by;
                $data[$i][6]=$p->created_at;
                $data[$i][7]=$p->updated_at;
                $i++;
            }
            return response()->json($data, 200);
        }
        else
        {
            return $parents;
        }
    }

    public function get_children(Request $request){
        $parent_type=$request->input('parent_type');
        $parent_name=$request->input('parent_name');
        $parent=Location::where('type',$parent_type)->where('name',$parent_name)->first();
        if(isset($parent))
        {
            $children=Location::where('parent_id',$parent->id)->orderBy('name','asc')->get();
        }
        else
        {
            $children=null;
        }
        return response()->json($children, 200);
    }

    public function get_thana_by_district(Request $request){
        $user_detail_ctrl=new UserDetailController();
        $parent_name=$request->input('parent_name');
        $parent=Location::where('type','district')->where('name',$parent_name)->first();
        if(isset($parent))
        {
            $individual_thanas=Location::where('parent_id',$parent->id)->whereIn('id',$user_detail_ctrl->get_user_access_id(Auth::user()->id,'fetch_permit','thana'))->where('type','thana');
            $zone=Location::where('parent_id',$parent->id)->where('type','zone')->get('id');
            $zone_arr=array();
            foreach ($zone as $z){
                array_push($zone_arr,$z->id);
            }
            $children=Location::whereIn('parent_id',$zone_arr)->whereIn('id',$user_detail_ctrl->get_user_access_id(Auth::user()->id,'fetch_permit','thana'))->union($individual_thanas)->orderBy('id','asc')->get();
        }
        else
        {
            $children=null;
        }
        return response()->json($children, 200);
    }

    public function get_district_by_division(Request $request){
        $user_detail_ctrl=new UserDetailController();
        $division_names=$request->input('division_names');
        $division=Location::where('type','division')->whereIn('name',$division_names)->get();
        if(isset($division)&&count($division)>0){
            $division_arr=array();
            foreach ($division as $d){
                array_push($division_arr,$d->id);
            }
            $children=Location::whereIn('parent_id',$division_arr)->whereIn('id',$user_detail_ctrl->get_user_access_id(Auth::user()->id,'fetch_permit','district'))->where('type','district')->get();
        }
        else
        {
            $children=null;
        }
        return response()->json($children, 200);
    }
    public function get_zone_by_district(Request $request){
        $user_detail_ctrl=new UserDetailController();
        $district_names=$request->input('district_names');
        $district=Location::where('type','district')->whereIn('name',$district_names)->get();
        if(isset($district)&&count($district)>0){
            $district_arr=array();
            foreach ($district as $d){
                array_push($district_arr,$d->id);
            }
            $children=Location::whereIn('parent_id',$district_arr)->whereIn('id',$user_detail_ctrl->get_user_access_id(Auth::user()->id,'fetch_permit','zone'))->where('type','zone')->get();
        }
        else
        {
            $children=null;
        }
        return response()->json($children, 200);
    }

    public function get_thana_by_zone(Request $request){
        $user_detail_ctrl=new UserDetailController();
        $zone_names=$request->input('zone_names');
        $district_names=$request->input('district_names');
        $others=0;
        if(in_array('others',$zone_names)){
            $others=1;
            $temp_zone=array();
            foreach ($zone_names as $zone){
                if($zone!='others'){
                    array_push($temp_zone,$zone);
                }
            }
            $zone_names=$temp_zone;
        }
        $zone=Location::where('type','zone')->whereIn('name',$zone_names)->get();
        $district=Location::where('type','district')->whereIn('name',$district_names)->get();
        if((isset($zone)&&count($zone)>0)||$others==1){
            $zone_arr=array();
            foreach ($zone as $z){
                array_push($zone_arr,$z->id);
            }
            $district_arr=array();
            foreach ($district as $d){
                array_push($district_arr,$d->id);
            }

            if(isset($zone)&&count($zone)>0&&$others==1)
            {
                $individual_thanas=Location::whereIn('parent_id',$district_arr)->whereIn('id',$user_detail_ctrl->get_user_access_id(Auth::user()->id,'fetch_permit','thana'))->where('type','thana');
                $children=Location::whereIn('parent_id',$zone_arr)->whereIn('id',$user_detail_ctrl->get_user_access_id(Auth::user()->id,'fetch_permit','thana'))->where('type','thana')->union($individual_thanas)->get();
            }
            elseif (isset($zone)&&count($zone)>0&$others!=1){
                $children=Location::whereIn('parent_id',$zone_arr)->whereIn('id',$user_detail_ctrl->get_user_access_id(Auth::user()->id,'fetch_permit','thana'))->where('type','thana')->get();
            }
            else
            {
                $children=Location::whereIn('parent_id',$district_arr)->whereIn('id',$user_detail_ctrl->get_user_access_id(Auth::user()->id,'fetch_permit','thana'))->where('type','thana')->get();
            }
        }
        else
        {
            $children=null;
        }
        return response()->json($children, 200);
    }

    public function get_thanas_by_zones(Request $request)
    {

        $zones = $request->input('zones');
        if (isset($zones) && count($zones) > 0) {
            $zone = Location::where('type', 'zone')->whereIn('id', $zones)->get();
            if ((isset($zone) && count($zone) > 0)) {
                $zone_arr = array();
                foreach ($zone as $z) {
                    array_push($zone_arr, $z->id);
                }
                $children = Location::whereIn('parent_id', $zone_arr)->where('type', 'thana')->get()->toArray();

            } else {
                $children = array();
            }
        }
        else{
            $children = array();
        }
        return response()->json($children, 200);
    }
}
