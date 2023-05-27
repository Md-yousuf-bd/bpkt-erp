<?php

namespace App\Http\Controllers;

use App\Models\MeasurementUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

class MeasurementUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Measurement Unit List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('unit','active'),
            array('List','active')
        );
        return view('admin.unit.index',$data);
    }
    public  function listData(Request $request){
        $groups=MeasurementUnit::query();
        return DataTables::eloquent($groups)
            ->addIndexColumn()
            ->setRowId(function($coa){
                return 'row_'.$coa->id;
            })
            ->setRowData([
                'name' => function($coa) {
                    return $coa->name ?? '';
                },

                'data-updated_at' => function($coa) {
                    return otherHelper::change_date_format($coa->updated_at,true,'d-M-Y h:i A');
                },
            ])

            ->addColumn('action',  function($coa) {

                $option='<div style="width:140px;">';
                if(auth()->user()->can('edit-unit')){
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('unit.edit',[$coa->id]).'"  ><span class="fa fa-edit">  Edit</i></a></div>';
                }

                if (auth()->user()->can('delete-unit')){
                    $option .= '<div style=" float: right">
                                    <form action="'.route('unit.destroy',[$coa->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                }
                else{
                    $option .= '<div style=" "></div>';
                }
                $option .='</div>';
                return $option;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Measuring Unit";
        $data['breadcumb']=array(
            array('Home','home'),
            array('unit','active'),
            array('Add','active')
        );

        return view('admin.unit.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $unit = new MeasurementUnit();
        $unit->name = $request->input('name');
        $unit->short_name = $request->input('short_name');
        $unit->created_by = Auth::user()->id;
        $unit->save();
        Logs::store(Auth::user()->name.' Measuring Unit has been created successfull ','Add','success',Auth::user()->id,$unit->id,'Measuring Unit');
        return redirect()->route('unit.index')->with('success','Measuring Unit has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['editData'] = MeasurementUnit::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Measuring Unit";
        $data['breadcumb']=array(
            array('Home','home'),
            array('unit','active'),
            array('Edit','active')
        );
        $data['editData'] = MeasurementUnit::find($id);

        return view('admin.unit.edit',$data);
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
        $unit = MeasurementUnit::find($id);
        $unit->name = $request->input('name');
        $unit->short_name = $request->input('short_name');
        $unit->updated_by = Auth::user()->id;
        $unit->save();
        Logs::store(Auth::user()->name.' Measuring Unit has been Updated successfull ','Edit','success',Auth::user()->id,$unit->id,'Measuring Unit');
        return redirect()->route('unit.index')->with('success','Measuring Unit has been Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = MeasurementUnit::find($id);
        $unit->delete();
        Logs::store(Auth::user()->name.' Measuring Unit has been Deleted successfull ','Delete','success',Auth::user()->id,$unit->id,'Measuring Unit');
        return redirect()->route('unit.index')->with('success','Measuring Unit has been Deleted successfully.');

    }
}
