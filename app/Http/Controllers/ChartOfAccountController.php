<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\GroupAccount;
use App\Models\Lookup;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;
use DB;
class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Chart of Accounts List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('coa','active'),
            array('List','active')
        );

        return view('admin.coa.index',$data);
    }

    public function listData(Request $request){
        $coas=ChartOfAccount::query();

        return DataTables::eloquent($coas)
            ->addIndexColumn()
            ->setRowId(function($coa){
                return 'row_'.$coa->id;
            })
            ->setRowData([
                'id' => function($coa) {
                    return $coa->id ?? '';
                },
                'head' => function($coa) {
                    return $coa->head ?? '';
                },
                'type' => function($coa) {
                    return $coa->type ?? '';
                },
                'category' => function($coa) {
                    return $coa->category ?? '';
                },'group_name' => function($coa) {
                    return $coa->group_name ?? '';
                },
                'sub_category' => function($coa) {
                    return $coa->sub_category ?? '';
                },
                'sub_sub_category' => function($coa) {
                    return $coa->sub_sub_category ?? '';
                },
                'system_code' => function($coa) {
                    return $coa->system_code ?? '';
                },
                'data-updated_at' => function($coa) {
                    return otherHelper::change_date_format($coa->updated_at,true,'d-M-Y h:i A');
                },
            ])
            ->addColumn('status',  function($coa) {
                $status='';
                if($coa->status==1){
                    $status = '<span style="color:green"> Active</span>';
                }else{
                    $status = '<span style="color:red"> In-Active</span>';
                }

                return $status;
            })
            ->addColumn('action',  function($coa) {
                if(auth()->user()->can('edit-coa')){
                    return '<div style="width:100%; float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('coa.edit',[$coa->id]).'" target="_blank" ><span class="fa fa-edit">  Edit</i></a></div>';
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Chart of Accounts";
        $unit = GroupAccount::all();
        $data['breadcumb']=array(
            array('Home','home'),
            array('coa','coa.index'),
            array('Add','active')
        );
        $data['unit'] = $unit;
        $id = Lookup::where('name','Asset')->first();
        $data['category']=Lookup::where('parent_id','=',$id['id'])->where('child_id','=',0)->where('child_id_2',0)->where('group_id',0)->orderBy('id','desc')->get();

        return view('admin.coa.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $check = ChartOfAccount::where('head',$request->input('head'))->first();
        if($check !=null){
            return back()->with('warning',  'Data already exits!');
        }

        $system_code = ChartOfAccount::getCoaCode($request->input('type_id'));
        $coa = new ChartOfAccount();
        $coa->type_id = $request->input('type');
        $coa->type = $request->input('type_id');
        $coa->head = $request->input('head');
        $coa->category = $request->input('cat_txt');
        $coa->sub_category = $request->input('sub_cat_txt');
        $coa->sub_sub_category = $request->input('sub_sub_cat_txt');
        $coa->category_id = $request->input('category')!=''?$request->input('category'):0;
        $coa->group_id = $request->input('group_id')!=''?$request->input('group_id'):0;
        $coa->group_name = $request->input('group_name');
        $coa->sub_category_id = $request->input('sub_category')!=''?$request->input('sub_category'):0;
        $coa->sub_sub_category_id = $request->input('sub_sub_category')!=''?$request->input('sub_sub_category'):0;
        $coa->status = $request->input('status');
        $coa->reference = $request->input('reference');
        $coa->system_code = $system_code;
        $coa->save();
        Logs::store(Auth::user()->name.' has Added New chart of account '.$request->input('head'),'Add Chart of account','success',Auth::user()->id);
        return redirect()->route('coa.index')->with('success','Chart of account has been Added Successfully!');

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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['page_name']="Edit Chart of Accounts";
        $unit = GroupAccount::all();
        $data['breadcumb']=array(
            array('Home','home'),
            array('coa','coa.index'),
            array('Edit','active')
        );
        $data['unit'] = $unit;
        $data['editData']=ChartOfAccount::where('id',$id)->first();
        $ids = Lookup::where('name','Coa Category')->first();
        $data['category']=Lookup::where('parent_id','=',$data['editData']['group_id'])->where('child_id','=',0)->where('child_id_2',0)->orderBy('id','desc')->get();

        return view('admin.coa.edit',$data);

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
        $check = ChartOfAccount::where('head',$request->input('head'))->first();
        $coa = ChartOfAccount::find($id);
        if($check==null){
            $coa->head = $request->input('head');
        }
        $coa->category = $request->input('cat_txt');
        $coa->sub_category = $request->input('sub_cat_txt');
        $coa->sub_sub_category = $request->input('sub_sub_cat_txt');
        $coa->category_id = $request->input('category')!=''?$request->input('category'):'';
        $coa->sub_category_id = $request->input('sub_category')!=''?$request->input('sub_category'):'';
        $coa->sub_sub_category_id = $request->input('sub_sub_category')!=''?$request->input('sub_sub_category'):0;
        $coa->group_id = $request->input('group_id')!=''?$request->input('group_id'):0;
        $coa->group_name = $request->input('group_name');
        $coa->status = $request->input('status');
        $coa->reference = $request->input('reference');
        $coa->save();
        Logs::store(Auth::user()->name.' has updated  chart of account '.$request->input('head'),'Add Chart of account','success',Auth::user()->id,$id,'Chart of account');
        return redirect()->route('coa.index')->with('success','Chart of account has been updated  Successfully!');

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
    public function getCoaList($id){

       $ledger= ChartOfAccount::where('sub_category','=',$id)->get();
       if(count($ledger)>0){
           echo json_encode(array('ledger'=>$ledger,'flag'=>1));

       }else{
           echo json_encode(array('ledger'=>$ledger,'flag'=>0));
       }


    }
}
