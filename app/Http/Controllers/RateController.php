<?php

namespace App\Http\Controllers;

use App\Models\RateInfo;
use App\Models\RateInfoLog;
use App\Models\GroupAccount;
use App\Models\Lookup;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;
class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['page_name']="Rate Info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Rate Info','active'),
            array('List','active')
        );
        return view('admin.rate-info.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Rate Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Rate Info','rate.index'),
            array('Add','active')
        );

        return view('admin.rate-info.create',$data);
    }
    public function listData(Request $request){
        $groups=RateInfo::query();
        return DataTables::eloquent($groups)
            ->addIndexColumn()
            ->setRowId(function($coa){
                return 'row_'.$coa->id;
            })
            ->setRowData([
                'type' => function($coa) {
                    return $coa->type ?? '';
                },
                'name' => function($coa) {
                    return $coa->name ?? '';
                },
                'rate' => function($coa) {
                    return $coa->rate ?? '';
                },'off_type' => function($coa) {
                    return $coa->off_type ?? '';
                }, 'effective_date' => function($coa) {
                    return $coa->effective_date ?? '';
                },
                'data-updated_at' => function($coa) {
                    return otherHelper::change_date_format($coa->updated_at,true,'d-M-Y h:i A');
                },
            ])

            ->addColumn('action',  function($coa) {
                if(auth()->user()->can('edit-rate')){
                    return '<div style="width:100%; float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('rate.edit',[$coa->id]).'" ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                else{
                    return '<div style="width:100%; float: left;"></div>';
                }
            })
            ->rawColumns(['action','status'])
            ->toJson();

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $check = RateInfo::where('type','=',$request->input('type'))
            ->where('off_type','=',$request->input('off_type'))->first();
        if($check !=null){
            return back()->with('warning',  'Data Already Exits');

        }
        $asset = new RateInfo();
        $asset->type=$request->input('type');
        $asset->name=$request->input('name');
        $asset->rate=$request->input('rate');
        $asset->off_type=$request->input('off_type');
        $asset->effective_date=$request->input('effective_date');
        $asset->status=1;
        $asset->created_by= Auth::user()->id;
        $asset->save();
        Logs::store(Auth::user()->name.' Rate Info has been created successfull ','Add','success',Auth::user()->id,$asset->id,'Rate Info');
        return redirect()->route('rate.index')->with('success','Rate Info has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Rate Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Rate Info','rate.index'),
            array('Edit','active')
        );
        $data['editData']= RateInfo::where('id',$id)->first();
        return view('admin.rate-info.edit',$data);

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

        $rate = RateInfo::find($id);
        RateInfoLog::create([
            'name'=>$rate->name,
            'type'=>$rate->type,
            'rate'=>$rate->rate,
            'vat'=>$rate->vat,
            'off_type'=>$rate->off_type,
            'effective_date'=>$rate->effective_date,
            'effective_date_to'=> date('Y-m-d'),
            'created_by'=> $rate->created_by,
            'updated_by'=> Auth::user()->id,
            'status'=> 1,
            'updated_at'=> date('Y-m-d H:i:s')
        ]);

//        $rate->type=$request->input('type');
//        $rate->name=$request->input('name');
        $rate->rate=$request->input('rate');
        $rate->vat=$request->input('vat');
        $rate->off_type=$request->input('off_type');
        $rate->effective_date=$request->input('effective_date');
        $rate->status=1;
        $rate->updated_by= Auth::user()->id;
        $rate->updated_at= date('Y-m-d H:i:s');
        $rate->save();
        Logs::store(Auth::user()->name.' Rate Info has been updated','Edit','success',Auth::user()->id,$id,'Rate Info');
        return redirect()->route('rate.index')->with('success','Rate Info has been updated  successfully!');

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logView()
    {
        //
        $data['page_name']="Log Rate Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Rate Info','active'),
            array('List','active')
        );
        return view('admin.rate-info.log',$data);
    }

    public function logData(Request $request){
        $groups=RateInfoLog::query();
        return DataTables::eloquent($groups)
            ->addIndexColumn()
            ->setRowId(function($coa){
                return 'row_'.$coa->id;
            })
            ->setRowData([
                'type' => function($coa) {
                    return $coa->type ?? '';
                },
                'name' => function($coa) {
                    return $coa->name ?? '';
                },
                'rate' => function($coa) {
                    return $coa->rate ?? '';
                }, 'effective_date' => function($coa) {
                    return $coa->effective_date ?? '';
                },
                'data-updated_at' => function($coa) {
                    return otherHelper::change_date_format($coa->updated_at,true,'Y-m-d');
                },
            ])

            ->toJson();

    }
}
