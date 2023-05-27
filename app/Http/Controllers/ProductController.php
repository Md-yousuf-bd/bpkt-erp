<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Lookup;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductLog;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Response;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Product info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Product','active'),
            array('List','active')
        );

        return view('admin.products.index',$data);
    }
    /**
     * list data
     */
    public function listData(Request $request){

        $columns = array(
            0 =>'id',
            1 =>'product_name',
            2 =>'vendor_name',
            3 =>'brand_name',
            4 =>'size',
            5=> 'updated_at',
            5=> 'product_id',
            6=> 'id'
        );


        $in  = $request->all();

        $selected_shops=array();


        $order = 'id';
        $dir ='desc';

        $limit = $request->input('length');
        $start = $request->input('start');


        if(empty($request->input('search.value')))
        {
            $totalData = Product::query()
                ->where('created_by',Auth::user()->id)
                ->count();
            $totalFiltered = $totalData;
            $products = Product::query()
                ->where('created_by',Auth::user()->id)
                ->orderBy($order,$dir)
                ->offset($start)
                ->limit($limit)
                ->get();
        }else{
            $search = $request->input('search.value');
            $toltalRecord = Product::query()
                ->where('id','LIKE',"%{$search}%")
                ->orwhere('product_name','LIKE',"%{$search}%")
                ->orWhere('vendor_name','LIKE',"%{$search}%")
                ->where('created_by',Auth::user()->id)
                ->get();

            $totalFiltered = $totalData = sizeof($toltalRecord);

            $products = Product::query()
                ->where('id','LIKE',"%{$search}%")
                ->orwhere('product_name','LIKE',"%{$search}%")
                ->orWhere('vendor_name','LIKE',"%{$search}%")
                ->where('created_by',Auth::user()->id)
                ->orderBy($order,$dir)
                ->offset($start)
                ->limit($limit)
                ->get();
        }


        $data = array();
        if(!empty($products))
        {
            $i=$start+1;

            foreach ($products as $product)
            {

                $nestedData['id'] = $i++;
                $nestedData['product_id'] = '#'.$product->id;
                $nestedData['product_name'] = $product->product_name;
                $nestedData['vendor_name'] = $product->vendor_name ?? '';
                $nestedData['brand_name'] = $product->brand_name ?? '';
                $nestedData['size'] = ($product->size ?? '');//." ".$product->unit->name ?? '';
                $nestedData['status'] = $product->status == 1?'<span style="color:green"> Active</span>':'<span style="color:red"> In-Active</span>';
                $nestedData['updated_user'] =  $product->user->name ?? '';
                $nestedData['options'] = '<div style=" float: left; margin-bottom: -7px;margin-right: 5px;">
                                                <a class="btn btn-sm btn-info text-white" href="'.route("product.show",[$product->id]).'"><span class="fa fa-info"> Detail</span></a>
                                                </div>
                                                <div style=" float: left;margin-bottom: -7px; margin-right: 5px;">
                                                    <a class="btn btn-sm btn-primary" href="'.route("product.edit",[$product->id]).'"><span class="fa fa-edit"> Edit</span></a>
                                                </div>
                                                <div style=" float: left; margin-bottom: -7px; text-align: right;">
                                                    <form action="'.route("product.destroy",[$product->id]).'" method="post" class="">
                                                        '.csrf_field().'
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <input type="submit" class="btn btn-sm btn-danger" value="Delete" onclick="return confirm(\'All the entries in Database related with this Product will be deleted also. Do you really want to delete this?\');">
                                                    </form>
                                                </div>';
                $nestedData['updated_at'] = date('d-M-Y h:i:s A',strtotime($product->updated_at));
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Product Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Product','product.index'),
            array('Add','active')
        );
        $data['vendor'] = Vendor::all();
        $data['unit'] = MeasurementUnit::all();
        $data['ledger'] = ChartOfAccount::all();

        return view('admin.products.create',$data);
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

        if($checkData['product_name']==''){
            return back()->with('warning',  'Please Enter Product name');
        }
        if($checkData['vendor_id']==0){
            return back()->with('warning',  'Please Select Vendor');
        }

        $product = new Product();
        foreach ($checkData as $key => $value) {
            $product->$key = $value;
        }
        $product->created_by= Auth::user()->id;
        $product->save();
        Logs::store(Auth::user()->name.' New Product has been created successfull ','Add','success',Auth::user()->id,$product->id,'Product Info');
        return redirect()->route('product.index')->with('success','Product has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['page_name']="Show Product Details";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Product','product.index'),
            array('Edit','active')
        );

        $data['details'] = Product::find($id);

        return view('admin.products.details',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Product Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Product','active'),
            array('Edit','active')
        );
        $data['vendor'] = Vendor::all();
        $data['unit'] = MeasurementUnit::all();
        $data['ledger'] = ChartOfAccount::all();
        $data['editData'] = Product::find($id);

        return view('admin.products.edit',$data);
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

        if($checkData['product_name']==''){
            return back()->with('warning',  'Please Enter Product name');
        }
        if($checkData['vendor_id']==0){
            return back()->with('warning',  'Please Select Vendor');
        }
        $log = array();
         $product = Product::find($id);
        $log = collect($product);
        unset($log['id'], $log['created_at'], $log['updated_at']);
        $log['product_id'] = $id;
        $log['updated_by'] = Auth::user()->id;
        ProductLog::insert($log->toArray());

        foreach ($checkData as $key => $value) {
            $product->$key = $value;
        }
        $product->updated_by= Auth::user()->id;
        $product->save();
        Logs::store(Auth::user()->name.'   Product has been Updated successfull ','Update','success',Auth::user()->id,$product->id,'Product Info');
        return redirect()->route('product.index')->with('success','Product has been Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendor = Product::find($id);
        Logs::store(Auth::user()->name. ' - '.$vendor->vendor_name.' Product has been Deleted successfull ','Delete','success',Auth::user()->id,$id,'Product Info');
        $vendor->delete();
        return redirect()->route('product.index')->with('success','Product has been Deleted successfully.');


    }

    public function getVendorInfo($id){

        $vendor = Vendor::find($id);
        $brand=array();
        if($vendor['brand_name_1']!=''){
            $brand[1] = $vendor['brand_name_1'];
        }
        if($vendor['brand_name_2']!=''){
            $brand[2] = $vendor['brand_name_2'];
        }
        if($vendor['brand_name_3']!=''){
            $brand[3] = $vendor['brand_name_3'];
        }
        if($vendor['brand_name_4']!=''){
            $brand[4] = $vendor['brand_name_4'];
        }
        if($vendor['brand_name_5']!=''){
            $brand[5] = $vendor['brand_name_5'];
        }
        if($vendor['brand_name_6']!=''){
            $brand[6] = $vendor['brand_name_6'];
        }

        return Response::json(array('vendor'=>$vendor,'brand'=>$brand));

    }
}
