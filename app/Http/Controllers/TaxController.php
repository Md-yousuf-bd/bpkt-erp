<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Tax Info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Tax','active'),
            array('List','active')
        );

        return view('admin.tax.index',$data);
    }
    /**
     * list data
     */
    public function listData(Request $request){
        $tax=Tax::query();
        return DataTables::eloquent($tax)
            ->addIndexColumn()
            ->setRowId(function($row){
                return 'row_'.$row->id;
            })
            ->setRowData([
                'account_head' => function($row) {
                    return $row->account_head ?? '';
                },

                'section' => function($row) {
                    return $row->section ?? '';
                },
                'lower_limit' => function($row) {
                    return $row->lower_limit ?? '0';
                },
                'upper_limit' => function($row) {
                    return $row->upper_limit ?? '0';
                },

                'year' => function($row) {
                    return $row->year ?? '';
                },
                'rate' => function($row) {
                    return $row->rate ?? '0';
                },
                'data-created_by' => function($row) {
                    if(isset($row->user)){
                        return $row->user->name;
                    }else{
                        return 'None';
                    }

                },
                'data-updated_at' => function($row) {
                    return otherHelper::change_date_format($row->updated_at,true,'d-M-Y h:i A');
                },
            ])

            ->addColumn('action',  function($row) {
                $option='<div style="width:220px;">';
                if(auth()->user()->can('edit-tax')){
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('tax.edit',[$row->id]).'"  ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if(auth()->user()->can('read-tax')){
                    $option .='<div style="padding-left:5px;float: left;"><a class="btn btn-xs btn-success text-white text-sm" href="'.route('tax.show',[$row->id]).'"  ><span class="fa fa-edit">  Details</i></a></div>';
                }
                if (auth()->user()->can('delete-tax')){
                    $option .= '<div style=" float: right">
                                    <form action="'.route('tax.destroy',[$row->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
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
        $data['page_name']="Add Tax Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Tax','tax.index'),
            array('Add','active')
        );

        return view('admin.tax.create',$data);
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
        $tax = new Tax();
        foreach ($checkData as $key => $value) {
            $tax->$key = $value;
        }
        $tax->created_by= Auth::user()->id;
        $tax->save();
        Logs::store(Auth::user()->name.'New Tax has been created successfull ','Add','success',Auth::user()->id,$tax->id,'Tax Info');
        return redirect()->route('tax.index')->with('success','Tax has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['page_name']="Add Tax Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Tax','tax.index'),
            array('Add','active')
        );

        return view('admin.tax.create',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Tax Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Tax','tax.index'),
            array('Add','active')
        );
        $data['editData'] = Tax::find($id);

        return view('admin.tax.edit',$data);
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

        $checkData = $request->all();
        unset($checkData['_token']);
        $tax =  Tax::find($id);
        foreach ($checkData as $key => $value) {
            $tax->$key = $value;
        }
        $tax->updated_by= Auth::user()->id;
        $tax->save();
        Logs::store(Auth::user()->name.'Tax has been updated successfull ','Update','success',Auth::user()->id,$tax->id,'Tax Info');
        return redirect()->route('tax.index')->with('success','Tax has been Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tax =  Tax::find($id);
        Logs::store(Auth::user()->name. ' - '.$tax->account_head.' Tax has been Deleted successfull ','Delete','success',Auth::user()->id,$id,'Tax Info');
        $tax->delete();
        return redirect()->route('tax.index')->with('success','Tax has been Deleted successfully.');

    }
}
