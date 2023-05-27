<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Godown;
use App\Models\Journal;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Sales; //Tentative
use App\Models\StockAllocation; // Tentative
use App\Models\StockInvoice;
use App\Models\Customer;
use App\Models\GroupAccount;
use App\Models\Lookup;
use App\Models\Owner;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Matrix\Exception;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

class StockInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Purchase Invoice List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Stock Invoice','active'),
            array('List','active')
        );
        return view('admin.stock.stock_invoice_list',$data);
    }


        public function listData()
        {
       	 
        $data = StockInvoice::all();
        return DataTables::of($data)
             ->addColumn('action', function($StockInvoice){
                return '<div style=" float: left; margin-bottom: -7px;margin-right: 5px;">
						<div style="padding-left:5px;padding-right:5px;float: left;">
                                                <a target="_blank" style="color:#fff !important;" class="btn btn-xs btn-success text-white text-sm" href="'.route('stock-invoice.show',[$StockInvoice->id]).'"  > Invoice </i></a></div>
                                                </div>';
            })
            ->make(true);
    }

    function show($id)
    {
    	$data['page_name']="Purchase Invoice";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Purchase Invoice','stock-invoice.index'),
            array('Show','active')
        );

        $invoice_data = StockInvoice::find($id);

        $store_name = explode(',', $invoice_data->store_name);
        $data['store_name'] = reset($store_name);

        $vendor_id = explode(',', $invoice_data->vendor_id);
        $data['vendor_id'] = reset($vendor_id);

        $vendor_name = explode(',', $invoice_data->vendor_name);
        $data['vendor_name'] = reset($vendor_name);

        $vendor_data = Vendor::find($data['vendor_id']);
        $data['vendor_address'] = $vendor_data->owner_address;

        $purchase_ref_no = explode(',', $invoice_data->purchase_ref_no);
        $data['purchase_ref_no'] = reset($purchase_ref_no);

        $data['voucher_no'] = $invoice_data->voucher_no;
        $data['issue_date'] = $invoice_data->created_at;

        $data['purchase_details'] = Stock::where('voucher_no', '=', $data['voucher_no'])->get();

        $data['invoice_data'] = $invoice_data;
        
        return view('admin.stock.details',$data);
    }
}
