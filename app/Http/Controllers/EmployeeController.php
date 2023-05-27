<?php

namespace App\Http\Controllers;

use App\Models\MeasurementUnit;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Lookup;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Employee info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Employee','active'),
            array('List','active')
        );

        return view('admin.employee.index',$data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listData(Request $request){
        $employee=Employee::query();
        return DataTables::eloquent($employee)
            ->addIndexColumn()
            ->setRowId(function($row){
                return 'row_'.$row->id;
            })
            ->setRowData([
                'name' => function($row) {
                    return $row->name ?? '';
                },

                'employee_no' => function($row) {
                    return $row->employee_no ?? '';
                },
                'phone' => function($row) {
                    return $row->phone ?? '';
                },
                'dept_name' => function($row) {
                    return $row->dept_name ?? '';
                },
                'nid' => function($row) {
                    return $row->nid ?? '';
                },
                'designation' => function($row) {
                    return $row->designation ?? '';
                },
                'branch_name' => function($row) {
                    return $row->branch_name ?? '';
                },
                'rank_name' => function($row) {
                    return $row->rank_name ?? '';
                },
                'job_status' => function($row) {
                    return $row->job_status ?? '';
                },
                'data-updated_at' => function($row) {
                    return otherHelper::change_date_format($row->updated_at,true,'d-M-Y h:i A');
                },
            ])
            ->addColumn('status',  function($row) {
                $status='';
                if($row->status==1){
                    $status = '<span style="color:green"> Active</span>';
                }else{
                    $status = '<span style="color:red"> In-Active</span>';
                }

                return $status;
            })
            ->addColumn('action',  function($row) {
                $option='<div style="width:220px;">';
                if(auth()->user()->can('edit-employee')){
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('employee.edit',[$row->id]).'"  ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if(auth()->user()->can('read-employee')){
                    $option .='<div style="padding-left:5px;float: left;"><a class="btn btn-xs btn-success text-white text-sm" href="'.route('employee.show',[$row->id]).'"  ><span class="fa fa-edit">  Details</i></a></div>';
                }
                if (auth()->user()->can('delete-employee')){
                    $option .= '<div style=" float: right">
                                    <form action="'.route('employee.destroy',[$row->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                }
                else{
                    $option .= '<div style=" "></div>';
                }
                $option .='</div>';
                return $option;
            })
            ->rawColumns(['action','status'])
            ->toJson();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Employee info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Employee','employee.index'),
            array('Add','active')
        );
        $rank_id = Lookup::where('name','Rank')->first();
        $dept_id = Lookup::where('name','Department')->first();
        $branch = Lookup::where('name','Branch')->first();
        $data['measurement'] = MeasurementUnit::all();
        $data['rank']=Lookup::where('parent_id','=',$rank_id['id'])->orderBy('id','desc')->get();
        $data['department']=Lookup::where('parent_id','=',$dept_id['id'])->orderBy('id','desc')->get();
        $data['branch']=Lookup::where('parent_id','=',$branch['id'])->orderBy('id','desc')->get();

        return view('admin.employee.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkData = $request->all();
        unset($checkData['_token']);
        if($checkData['name']==''){
            return back()->with('warning',  'Please Enter Employee name');
        }
        if($request->input('email')!='' && !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('warning','Please Enter Valid Email');
        }
        $employee = new Employee();
        foreach ($checkData as $key => $value) {
            $employee->$key = $value;
        }
        $employee->created_by= Auth::user()->id;
        $employee->save();
        Logs::store(Auth::user()->name.'New Employee has been created successfull ','Add','success',Auth::user()->id,$employee->id,'Employee Info');
        return redirect()->route('employee.index')->with('success','Employee has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['page_name']="Employee info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Employee','employee.index'),
            array('List','active')
        );
        $data['details'] = Employee::find($id);

        return view('admin.employee.details',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Employee Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Employee','active'),
            array('Add','active')
        );
        $rank_id = Lookup::where('name','Rank')->first();
        $dept_id = Lookup::where('name','Department')->first();
        $branch = Lookup::where('name','Branch')->first();
        $data['measurement'] = MeasurementUnit::all();
        $data['rank']=Lookup::where('parent_id','=',$rank_id['id'])->orderBy('id','desc')->get();
        $data['department']=Lookup::where('parent_id','=',$dept_id['id'])->orderBy('id','desc')->get();
        $data['branch']=Lookup::where('parent_id','=',$branch['id'])->orderBy('id','desc')->get();
        $data['editData']= Employee::find($id);
        return view('admin.employee.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $checkData =  $request->all();
        if($checkData['name']==''){
            return back()->with('warning',  'Please Enter vendor name');
        }
        if($checkData['employee_no']==''){
            return back()->with('warning',  'Please Enter owner name');
        }
        if($request->input('email')!='' && !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('warning','Please Enter Valid Email');
        }
        unset($checkData['_token']);
        $employee  = Employee::find($id);
        foreach ($checkData as $key => $value) {
            $employee->$key = $value;
        }
        $employee->updated_by = Auth::user()->id;
        $employee->save();
        Logs::store(Auth::user()->name.' Employee has been Updated successfull ','Update','success',Auth::user()->id,$employee->id,'Employee Info');
        return redirect()->route('employee.index')->with('success','Employee has been Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
        Logs::store(Auth::user()->name. ' - '.$employee->name.' Employee has been Deleted successfull ','Delete','success',Auth::user()->id,$id,'Employee Info');
        $employee->delete();
        return redirect()->route('employee.index')->with('success','Employee has been Deleted successfully.');

    }
    public function admissionForm(){
        $data['page_name']="Admission Form Security Guird";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Employee','employee.index'),
            array('Add','active')
        );
        $rank_id = Lookup::where('name','Rank')->first();
        $dept_id = Lookup::where('name','Department')->first();
        $branch = Lookup::where('name','Branch')->first();
        $data['measurement'] = MeasurementUnit::all();
        $data['rank']=Lookup::where('parent_id','=',$rank_id['id'])->orderBy('id','desc')->get();
        $data['department']=Lookup::where('parent_id','=',$dept_id['id'])->orderBy('id','desc')->get();
        $data['branch']=Lookup::where('parent_id','=',$branch['id'])->orderBy('id','desc')->get();

        return view('admin.employee.admission-form',$data);
    }
}
