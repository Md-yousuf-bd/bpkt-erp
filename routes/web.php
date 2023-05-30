<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@index');
Route::get('/fill-onwer-info', 'DatafillController@fillOwnerId');
Route::get('/fill-fine-a', 'DatafillController@addFineFromBackup');


Route::group(['prefix'=>'','middleware'=>['auth','language']],function () {
    Route::get('fill-pre-fine', 'DatafillController@removeFixedFine');
    Route::get('create-pre-journal', 'DatafillController@makeJournalPre');
    Route::get('/fill-fine-a', 'DatafillController@addFineFromBackup');

});

Route::get('/qrcode/{text}',[
    'uses' => 'QRController@makeQrCode',
    'as'   => 'qrcode'
]);

Route::get('set-locale/{locale}', 'Controller@set_locale')->name('locale');
//Route::get('make-journal', 'CronJobController@dueBillCreate');
Route::get('auto-rent',['as'=>'auto-rent','uses'=>'CronJobController@rentAutoIncrement']);
Route::get('rent-interest',['as'=>'rent-interest','uses'=>'CronJobController@rentInterestCreate'] );
Route::get('make-journal',['as'=>'make-journal','uses'=>'CronJobController@dueBillCreate'] );
Route::get('test-unit',['as'=>'test-unit','uses'=>'CronJobController@testMake'] );

Route::get('assetsss',['as'=>'assetsss','uses'=>'AssetController@billSDate'] );
Auth::routes();

Route::group(['prefix'=>'','middleware'=>['auth','language']],function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile-edit',['as'=>'profile_edit','middleware'=>['permission:edit-profile'],'uses'=>'UserDetailController@profile_edit']);
    Route::patch('/profile-update',['as'=>'profile_update','middleware'=>['permission:edit-profile'],'uses'=>'UserDetailController@profile_update']);
    Route::get('/change-password',['as'=>'change_password','middleware'=>['permission:change-password'],'uses'=>'UserDetailController@change_password']);
    Route::patch('/update-password',['as'=>'update_password','middleware'=>['permission:change-password'],'uses'=>'UserDetailController@update_password']);
    Route::post('register_excel',['uses'=>'UserDetailController@register_excel'])->name('register_excel');
    Route::group(['prefix'=>'owner','as'=>'owner'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-owner'],'uses'=>'OwnerController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-owner'],'uses'=>'OwnerController@index']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-owner'],'uses'=>'OwnerController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-owner'],'uses'=>'OwnerController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-owner'],'uses'=>'OwnerController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-owner'],'uses'=>'OwnerController@store']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-owner'],'uses'=>'OwnerController@destroy']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-owner'],'uses'=>'OwnerController@update']);
    });
    Route::group(['prefix'=>'customer','as'=>'customer'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-customer'],'uses'=>'CustomerController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-customer'],'uses'=>'CustomerController@index']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-customer'],'uses'=>'CustomerController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-customer'],'uses'=>'CustomerController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-customer'],'uses'=>'CustomerController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-customer'],'uses'=>'CustomerController@store']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-customer'],'uses'=>'CustomerController@destroy']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-customer'],'uses'=>'CustomerController@update']);
        Route::get('/read-excel',['as'=>'.read-excel','middleware'=>['permission:update-customer'],'uses'=>'CustomerController@readExcel']);
    });

    Route::group(['prefix'=>'vendor','as'=>'vendor'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-vendor'],'uses'=>'VendorController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-vendor'],'uses'=>'VendorController@index']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-vendor'],'uses'=>'VendorController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-vendor'],'uses'=>'VendorController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-vendor'],'uses'=>'VendorController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-vendor'],'uses'=>'VendorController@store']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-vendor'],'uses'=>'VendorController@destroy']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-vendor'],'uses'=>'VendorController@update']);
    });

    Route::group(['prefix'=>'unit','as'=>'unit'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-unit'],'uses'=>'MeasurementUnitController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-unit'],'uses'=>'MeasurementUnitController@index']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-unit'],'uses'=>'MeasurementUnitController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-unit'],'uses'=>'MeasurementUnitController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-unit'],'uses'=>'MeasurementUnitController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-unit'],'uses'=>'MeasurementUnitController@store']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-unit'],'uses'=>'MeasurementUnitController@destroy']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-unit'],'uses'=>'MeasurementUnitController@update']);
    });

    Route::group(['prefix'=>'group-account','as'=>'group-account'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-group-account'],'uses'=>'GroupAccountController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-group-account'],'uses'=>'GroupAccountController@index']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-group-account'],'uses'=>'GroupAccountController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-group-account'],'uses'=>'GroupAccountController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-group-account'],'uses'=>'GroupAccountController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:create-group-account'],'uses'=>'GroupAccountController@update']);
    });
    Route::group(['prefix'=>'godown','as'=>'godown'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-godown'],'uses'=>'GodownController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-godown'],'uses'=>'GodownController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-godown'],'uses'=>'GodownController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-godown'],'uses'=>'GodownController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:edit-godown'],'uses'=>'GodownController@update']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:create-godown'],'uses'=>'GodownController@edit']);
    });
    Route::group(['prefix'=>'advertisement','as'=>'advertisement'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-advertisement'],'uses'=>'AdvertismentController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-advertisement'],'uses'=>'AdvertismentController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-advertisement'],'uses'=>'AdvertismentController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-advertisement'],'uses'=>'AdvertismentController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:edit-advertisement'],'uses'=>'AdvertismentController@update']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:create-advertisement'],'uses'=>'AdvertismentController@edit']);
        Route::get('/read-excel',['as'=>'.read-excel','middleware'=>['permission:create-advertisement'],'uses'=>'AdvertismentController@readExcel']);
        Route::get('/get-customer-ad/{id}',['as'=>'.get-customer-ad','middleware'=>['permission:create-advertisement'],'uses'=>'AdvertismentController@getAddCode']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-advertisement'],'uses'=>'AdvertismentController@destroy']);

    }
    );
    Route::group(['prefix'=>'coa','as'=>'coa'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-coa'],'uses'=>'ChartOfAccountController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-coa'],'uses'=>'ChartOfAccountController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-coa'],'uses'=>'ChartOfAccountController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-coa'],'uses'=>'ChartOfAccountController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:edit-coa'],'uses'=>'ChartOfAccountController@update']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:create-coa'],'uses'=>'ChartOfAccountController@edit']);
        Route::get('/get-coa-list/{id}',['as'=>'.get-coa-list','uses'=>'ChartOfAccountController@getCoaList']);

    });
    Route::group(['prefix'=>'product','as'=>'product'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-product'],'uses'=>'ProductController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-product'],'uses'=>'ProductController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-product'],'uses'=>'ProductController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-product'],'uses'=>'ProductController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-product'],'uses'=>'ProductController@update']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-product'],'uses'=>'ProductController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-product'],'uses'=>'ProductController@edit']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-product'],'uses'=>'ProductController@destroy']);
        Route::get('/get-vendor-info/{id}',['as'=>'.get-vendor-info','middleware'=>['permission:create-product'],'uses'=>'ProductController@getVendorInfo']);
    }
    );
    Route::group(['prefix'=>'tax','as'=>'tax'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-tax'],'uses'=>'TaxController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-tax'],'uses'=>'TaxController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-tax'],'uses'=>'TaxController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-tax'],'uses'=>'TaxController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-tax'],'uses'=>'TaxController@update']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-tax'],'uses'=>'TaxController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-tax'],'uses'=>'TaxController@edit']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-tax'],'uses'=>'TaxController@destroy']);
        Route::get('/get-vendor-info/{id}',['as'=>'.get-vendor-info','middleware'=>['permission:create-tax'],'uses'=>'TaxController@getVendorInfo']);
    }
    );
    Route::group(['prefix'=>'income','as'=>'income'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-income'],'uses'=>'IncomeController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-income'],'uses'=>'IncomeController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-income'],'uses'=>'IncomeController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-income'],'uses'=>'IncomeController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-income'],'uses'=>'IncomeController@update']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-income'],'uses'=>'IncomeController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-income'],'uses'=>'IncomeController@edit']);
        Route::get('/get-credit-period/{id}',['as'=>'.get-credit-period','middleware'=>['permission:edit-income'],'uses'=>'IncomeController@getCustomer']);
        Route::get('/journal/{id}',['as'=>'.journal','middleware'=>['permission:edit-income'],'uses'=>'IncomeController@journal']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-income'],'uses'=>'IncomeController@destroy']);
        Route::get('/get-vendor-info/{id}',['as'=>'.get-vendor-info','middleware'=>['permission:create-income'],'uses'=>'IncomeController@getVendorInfo']);
    });

    Route::group(['prefix'=>'billing','as'=>'billing'],function () {
        Route::get('/show-bill-wrong-data-fill',['as'=>'.show-bill-wrong-data-fill','middleware'=>['permission:create-billing'],'uses'=>'BillingController2@showWrongList']);
        Route::get('/bill-wrong-data-fill/{id}',['as'=>'.fill-wrong-data','middleware'=>['permission:create-billing'],'uses'=>'BillingController2@createJournalForNew']);
        Route::get('/fixed-fine_fill',['as'=>'.fixed-fine_fill','middleware'=>['permission:create-billing'],'uses'=>'BillingController2@fillFineAmount']);
        Route::get('/remove-fine-amount',['as'=>'.remove-fine-amount','middleware'=>['permission:delete-fine-amount'],'uses'=>'BillingController2@removeFineAmountView']);
        Route::get('/remove-fine-amount-bill/{id}',['as'=>'.remove-fine-amount-bill','middleware'=>['permission:delete-fine-amount'],'uses'=>'BillingController2@removeFineAmount']);

        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-billing'],'uses'=>'BillingController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-billing'],'uses'=>'BillingController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-billing'],'uses'=>'BillingController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-billing'],'uses'=>'BillingController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-billing'],'uses'=>'BillingController@update']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-billing'],'uses'=>'BillingController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-billing'],'uses'=>'BillingController@edit']);
        Route::get('/get-credit-period/{id}',['as'=>'.get-credit-period','uses'=>'BillingController@getCustomer']);
        Route::get('/journal/{id}',['as'=>'.journal','middleware'=>['permission:edit-billing'],'uses'=>'BillingController@journal']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-billing'],'uses'=>'BillingController@destroy']);
        Route::get('/get-vendor-info/{id}',['as'=>'.get-vendor-info','middleware'=>['permission:create-billing'],'uses'=>'BillingController@getVendorInfo']);
        Route::get('/addM',['as'=>'.createM','middleware'=>['permission:create-billing'],'uses'=>'BillingController2@create']);
        Route::post('/storeM',['as'=>'.storeM','middleware'=>['permission:create-billing'],'uses'=>'BillingController2@store']);
        Route::post('/get-cur_reading',['as'=>'.get-cur_reading','middleware'=>['permission:create-billing'],'uses'=>'BillingController2@getCurReading']);
        Route::get('/billing-fine-fill',['as'=>'.billing-fine-fill','uses'=>'BillingController@fillFineAmount']);

    });
    Route::group(['prefix'=>'payment','as'=>'payment'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-payment'],'uses'=>'PaymentController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-payment'],'uses'=>'PaymentController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-payment'],'uses'=>'PaymentController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-payment'],'uses'=>'PaymentController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-payment'],'uses'=>'PaymentController@update']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-payment'],'uses'=>'PaymentController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-payment'],'uses'=>'PaymentController@edit']);
        Route::get('/get-credit-period/{id}',['as'=>'.get-credit-period','uses'=>'PaymentController@getCustomer']);
        Route::get('/journal/{id}',['as'=>'.journal','middleware'=>['permission:read-payment'],'uses'=>'PaymentController@journal']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-payment'],'uses'=>'PaymentController@destroy']);
        Route::get('/get-vendor-info/{id}',['as'=>'.get-vendor-info','middleware'=>['permission:create-payment'],'uses'=>'PaymentController@getVendorInfo']);
        Route::get('/get-coa/{id}',['as'=>'.get-coa','uses'=>'PaymentController@getCoaList']);
        Route::get('/get-payment-coa/{id}',['as'=>'.get-coa','uses'=>'PaymentController@getPaymentLdger']);
        Route::get('/get-vendor-invoice/{id}',['as'=>'.get-vendor-invoice','uses'=>'StockController@getVendorInvoice']);
    });
    Route::group(['prefix'=>'payable','as'=>'payable'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-payable'],'uses'=>'PayableController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-payable'],'uses'=>'PayableController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-payable'],'uses'=>'PayableController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-payable'],'uses'=>'PayableController@store']);
        Route::post('/payments',['as'=>'.payments','middleware'=>['permission:payment-payable'],'uses'=>'PayableController@payment']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-payable'],'uses'=>'PayableController@update']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-payable'],'uses'=>'PayableController@show']);
        Route::get('/payment/{id}',['as'=>'.payment','middleware'=>['permission:payment-payable'],'uses'=>'PayableController@edit']);
        Route::get('/get-credit-period/{id}',['as'=>'.get-credit-period','uses'=>'PayableController@getCustomer']);
        Route::get('/journal/{id}',['as'=>'.journal','middleware'=>['permission:read-payable'],'uses'=>'PayableController@journal']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-payable'],'uses'=>'PayableController@destroy']);
        Route::get('/get-vendor-info/{id}',['as'=>'.get-vendor-info','middleware'=>['permission:create-payment'],'uses'=>'PayableController@getVendorInfo']);
        Route::get('/get-coa/{id}',['as'=>'.get-coa','uses'=>'PayableController@getCoaList']);
        Route::get('/get-payment-coa/{id}',['as'=>'.get-coa','uses'=>'PayableController@getPaymentLdger']);
        Route::get('/get-vendor-invoice/{id}',['as'=>'.get-vendor-invoice','uses'=>'PayableController@getVendorInvoice']);
    });

        Route::group(['prefix'=>'stock','as'=>'stock'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-stock'],'uses'=>'StockController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-stock'],'uses'=>'StockController@index']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-stock'],'uses'=>'StockController@show']);
        Route::get('/journal/{id}',['as'=>'.journal','middleware'=>['permission:read-stock'],'uses'=>'StockController@journal']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-stock'],'uses'=>'StockController@edit']);
        Route::get('/get-product/{id}/{ref}',['as'=>'.get-product','middleware'=>['permission:create-stock'],'uses'=>'StockController@getProductInfo']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-stock'],'uses'=>'StockController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-stock'],'uses'=>'StockController@store']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-stock'],'uses'=>'StockController@destroy']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-stock'],'uses'=>'StockController@update']);
        Route::get('/get-ledger/{id}',['as'=>'.get-ledger','uses'=>'StockController@getAllLedger']);
    });
    Route::group(['prefix'=>'stock-invoice','as'=>'stock-invoice'],function () {
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-stock'],'uses'=>'StockInvoiceController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-stock'],'uses'=>'StockInvoiceController@listData']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-stock'],'uses'=>'StockInvoiceController@show']);
    });

    Route::group(['prefix'=>'journal','as'=>'journal'],function () {
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-journal'],'uses'=>'JournalController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-journal'],'uses'=>'JournalController@listData']);
    }
    );
    Route::group(['prefix'=>'cash-collection','as'=>'cash-collection'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-cash-collection'],'uses'=>'CashCollectionController@create']);
        Route::get('/add-new',['as'=>'.create-new','middleware'=>['permission:create-cash-collection'],'uses'=>'CashCollectionController@create_new']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-cash-collection'],'uses'=>'CashCollectionController@index']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-cash-collection'],'uses'=>'CashCollectionController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-cash-collection'],'uses'=>'CashCollectionController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-cash-collection'],'uses'=>'CashCollectionController@update']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-cash-collection'],'uses'=>'CashCollectionController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-cash-collection'],'uses'=>'CashCollectionController@edit']);
        Route::post('/due-invoice',['as'=>'.due-invoice','middleware'=>['permission:create-cash-collection'],'uses'=>'CashCollectionController@dueInvoice']);
        Route::get('/journal/{id}',['as'=>'.journal','middleware'=>['permission:read-cash-collection'],'uses'=>'CashCollectionController@journal']);
        Route::get('/mr/{id}',['as'=>'.mr','middleware'=>['permission:read-cash-collection'],'uses'=>'CashCollectionController@MrView']);
        Route::get('/mr-view/{id}',['as'=>'.mr-view','middleware'=>['permission:read-cash-collection'],'uses'=>'CashCollectionController@MrViewTwo']);
        Route::post('/get-invoice-details',['as'=>'.get-invoice-details','uses'=>'CashCollectionController@getInvoiceDetails']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-cash-collection'],'uses'=>'CashCollectionController@destroy']);
        Route::get('/get-vendor-info/{id}',['as'=>'.get-vendor-info','middleware'=>['permission:create-cash-collection'],'uses'=>'CashCollectionController@getVendorInfo']);
        Route::get('/get-security-deposit/{id}',['as'=>'.get-security-deposit','middleware'=>['permission:create-cash-collection'],'uses'=>'CashCollectionController@getSecuirityDeposit']);
        Route::get('/get-customer-invoice/{id}',['as'=>'.get-customer-invoice','middleware'=>['permission:create-cash-collection'],'uses'=>'CashCollectionController@getCustomerInvoice']);
    }
    );

    Route::group(['prefix'=>'report','as'=>'report'],function () {
        Route::get('/gl',['as'=>'.gl','middleware'=>['permission:read-gl'],'uses'=>'ReportController@generalLedger']);
        Route::post('/show-gl',['as'=>'.show-gl','middleware'=>['permission:read-gl'],'uses'=>'ReportController@generalLedgerShow']);
        Route::match(['get','post'],'tb',['as'=>'.tb','middleware'=>['permission:read-tb'],'uses'=>'ReportController@trialBalance']);
        Route::get('/bs',['as'=>'.bs','middleware'=>['permission:read-bs'],'uses'=>'ReportController@balanceSheet']);
        Route::get('/ledger/{type}',['as'=>'.ledger','uses'=>'ReportController@ledger']);
        Route::match(['get','post'],'rs',['as'=>'.rs','middleware'=>['permission:read-rs'],'uses'=>'ReportController@receivableStatement']);
        Route::match(['get','post'],'is',['as'=>'.is','middleware'=>['permission:read-is'],'uses'=>'ReportController@incomeStatement']);
        Route::match(['get','post'],'bs',['as'=>'.bs','middleware'=>['permission:read-bs'],'uses'=>'ReportController@balanceSheet']);
        Route::match(['get','post'],'el',['as'=>'.el','middleware'=>['permission:read-el'],'uses'=>'ReportController@meterReading']);
        Route::match(['get','post'],'els',['as'=>'.els','middleware'=>['permission:read-meter-el'],'uses'=>'ReportController@meterReadingStatement']);
        Route::match(['get','post'],'aar',['as'=>'.aar','middleware'=>['permission:read-aar'],'uses'=>'ReportController@assetAllotmentReport']);
        Route::match(['get','post'],'dr',['as'=>'.dr','middleware'=>['permission:read-dr'],'uses'=>'ReportController@duesReport']);
//        Route::match(['get','post'],'cs',['as'=>'.cs','middleware'=>['permission:read-cs'],'uses'=>'ReportController@collectionStatementReport']);
        Route::match(['get','post'],'rcs',['as'=>'.rcs','middleware'=>['permission:read-rcs'],'uses'=>'ReportController@receivableCollectionStatementReport']);
        Route::match(['get','post'],'cs',['as'=>'.cs','middleware'=>['permission:read-cs'],'uses'=>'ReportController@CollectionStatementReport']);
        Route::match(['get','post'],'asset-report',['as'=>'.asset-report','middleware'=>['permission:read-asset-report'],'uses'=>'ReportController@AssetStatementReport']);
        Route::match(['get','post'],'bwcr',['as'=>'.bwcr','middleware'=>['permission:read-bwcr'],'uses'=>'ReportController@BillWiseCustomerReport']);
        Route::match(['get','post'],'csr',['as'=>'.csr','middleware'=>['permission:read-csr'],'uses'=>'ReportController@DailyCollectionReport']);
        Route::match(['get','post'],'security-deposit',['as'=>'.security-deposit','middleware'=>['permission:read-security-deposit-report'],'uses'=>'ReportController@SecurityDeositReport']);
        Route::match(['get','post'],'rate-history',['as'=>'.rate-history','middleware'=>['permission:read-rate-history'],'uses'=>'ReportController@RateHisoryReport']);
        Route::match(['get','post'],'receipt-payment',['as'=>'.receipt-payment','middleware'=>['permission:read-receipt-payment'],'uses'=>'ReportController@ReceiptPaymentReport']);
//        Route::get('/show-dues-details/{id}',['as'=>'.show-dues-details','middleware'=>['permission:read-rcs'],'uses'=>'ReportController@receivableCollectionStatementReportDetails']);
        Route::get('/show-customer-shop/{id}',['as'=>'.show-customer-shop','uses'=>'AssetController@getAssetNo']);
        Route::get('/show-dues-details/{id}/{category}/{type}',['as'=>'.show-dues-details','middleware'=>['permission:read-rcs'],'uses'=>'ReportController@receivableCollectionStatementReportDetails']);
        Route::get('/show-deduction-details/{ledger_id}/{shop_no}',['as'=>'.show-deduction-details','uses'=>'ReportController@showAdvanceDeductionDetails']);
        Route::get('generate-pdf','ReportController@generatePDF');
        Route::match(['get','post'],'due-statement-customer',['as'=>'.due-statement-customer','middleware'=>['permission:read-due-statement-customer'],'uses'=>'ReportController@DueStatementCustomerWiseReport']);
        Route::match(['get','post'],'due-statement-shop',['as'=>'.due-statement-shop','middleware'=>['permission:read-due-statement-shop'],'uses'=>'ReportController@DueStatementShopWiseReport']);

    });


    Route::group(['prefix'=>'employee','as'=>'employee'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-employee'],'uses'=>'EmployeeController@create']);
        Route::get('/admission-form',['as'=>'.admission-form','middleware'=>['permission:create-employee'],'uses'=>'EmployeeController@admissionForm']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-employee'],'uses'=>'EmployeeController@index']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-employee'],'uses'=>'EmployeeController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-employee'],'uses'=>'EmployeeController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-employee'],'uses'=>'EmployeeController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-employee'],'uses'=>'EmployeeController@store']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-employee'],'uses'=>'EmployeeController@destroy']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-employee'],'uses'=>'EmployeeController@update']);
    });

    Route::group(['prefix'=>'manual-journal','as'=>'manual-journal'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-manual-journal'],'uses'=>'ManualJournalController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-manual-journal'],'uses'=>'ManualJournalController@index']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-manual-journal'],'uses'=>'ManualJournalController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-manual-journal'],'uses'=>'ManualJournalController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-manual-journal'],'uses'=>'ManualJournalController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-manual-journal'],'uses'=>'ManualJournalController@store']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-manual-journal'],'uses'=>'ManualJournalController@destroy']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-manual-journal'],'uses'=>'ManualJournalController@update']);
    });

    Route::group(['prefix'=>'security-deposit','as'=>'security-deposit'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-security-deposit'],'uses'=>'SecurityDepositController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-security-deposit'],'uses'=>'SecurityDepositController@index']);
        Route::get('/show/{id}',['as'=>'.show','middleware'=>['permission:read-security-deposit'],'uses'=>'SecurityDepositController@show']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-security-deposit'],'uses'=>'SecurityDepositController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-security-deposit'],'uses'=>'SecurityDepositController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-security-deposit'],'uses'=>'SecurityDepositController@store']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-security-deposit'],'uses'=>'SecurityDepositController@destroy']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:update-security-deposit'],'uses'=>'SecurityDepositController@update']);
        Route::get('/mr/{id}',['as'=>'.mr','middleware'=>['permission:read-security-deposit'],'uses'=>'SecurityDepositController@getMrView']);
    });


    Route::group(['prefix'=>'assets','as'=>'assets'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-assets'],'uses'=>'AssetController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-assets'],'uses'=>'AssetController@index']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-assets'],'uses'=>'AssetController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-assets'],'uses'=>'AssetController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-assets'],'uses'=>'AssetController@store']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-assets'],'uses'=>'AssetController@destroy']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:create-assets'],'uses'=>'AssetController@update']);
        Route::post('/get-parent',['as'=>'.get-parent','uses'=>'AssetController@checkParent']);
    });
    Route::group(['prefix'=>'meter','as'=>'meter'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-meter'],'uses'=>'MeterController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-meter'],'uses'=>'MeterController@index']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-meter'],'uses'=>'MeterController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-meter'],'uses'=>'MeterController@listData']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-meter'],'uses'=>'MeterController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:create-meter'],'uses'=>'MeterController@update']);
        Route::delete('/destroy/{id}',['as'=>'.destroy','middleware'=>['permission:delete-meter'],'uses'=>'MeterController@destroy']);

    });
    Route::group(['prefix'=>'rate','as'=>'rate'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-rate'],'uses'=>'RateController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-rate'],'uses'=>'RateController@index']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-rate'],'uses'=>'RateController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-rate'],'uses'=>'RateController@listData']);
        Route::post('/log',['as'=>'.log','middleware'=>['permission:read-rate'],'uses'=>'RateController@logData']);
        Route::get('/log-view',['as'=>'.log-view','middleware'=>['permission:read-rate'],'uses'=>'RateController@logView']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-rate'],'uses'=>'RateController@store']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:create-rate'],'uses'=>'RateController@update']);
    });

    Route::group(['prefix'=>'bulk','as'=>'bulk'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-bulk'],'uses'=>'BulkEntryController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-bulk'],'uses'=>'BulkEntryController@index']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-bulk'],'uses'=>'BulkEntryController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-bulk'],'uses'=>'BulkEntryController@listData']);
        Route::post('/log',['as'=>'.log','middleware'=>['permission:read-bulk'],'uses'=>'BulkEntryController@logData']);
        Route::get('/log-view',['as'=>'.log-view','middleware'=>['permission:read-bulk'],'uses'=>'BulkEntryController@logView']);
        Route::post('/show-all-customer',['as'=>'.show-all-customer','uses'=>'BulkEntryController@showCustomer']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-bulk'],'uses'=>'BulkEntryController@store']);
        Route::post('/print-options',['as'=>'.print-options','uses'=>'BulkEntryController@printOptions']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:create-bulk'],'uses'=>'BulkEntryController@update']);
        Route::get('upload-old-invoice',['as'=>'.upload-old-invoice', 'uses'=>'BulkEntryController@makePreviousBill']);

    });

    ////new temp
    Route::group(['prefix'=>'bulk','as'=>'bulk'],function () {
        Route::get('/add',['as'=>'.create','middleware'=>['permission:create-bulk'],'uses'=>'BulkEntryController@create']);
        Route::get('/index',['as'=>'.index','middleware'=>['permission:read-bulk'],'uses'=>'BulkEntryController@index']);
        Route::get('/edit/{id}',['as'=>'.edit','middleware'=>['permission:edit-bulk'],'uses'=>'BulkEntryController@edit']);
        Route::post('/list',['as'=>'.list','middleware'=>['permission:read-bulk'],'uses'=>'BulkEntryController@listData']);
        Route::post('/log',['as'=>'.log','middleware'=>['permission:read-bulk'],'uses'=>'BulkEntryController@logData']);
        Route::get('/log-view',['as'=>'.log-view','middleware'=>['permission:read-bulk'],'uses'=>'BulkEntryController@logView']);
        Route::post('/show-all-customer',['as'=>'.show-all-customer','uses'=>'BulkEntryController@showCustomer']);
        Route::post('/store',['as'=>'.store','middleware'=>['permission:create-bulk'],'uses'=>'BulkEntryController@store']);
        Route::post('/print-options',['as'=>'.print-options','uses'=>'BulkEntryController@printOptions']);
        Route::post('/update/{id}',['as'=>'.update','middleware'=>['permission:create-bulk'],'uses'=>'BulkEntryController@update']);
        Route::get('upload-old-invoice',['as'=>'.upload-old-invoice', 'uses'=>'BulkEntryController@makePreviousBill']);

    });
    Route::group(['prefix'=>'settings','as'=>'settings'],function () {
        Route::group(['prefix'=>'user','as'=>'.user'],function () {
            Route::get('/list',['as'=>'.index','middleware'=>['permission:read-user'],'uses'=>'UserDetailController@index']);
            Route::post('/get-list', ['as' => '.get_index', 'middleware' => ['permission:read-user'], 'uses' => 'UserDetailController@get_index']);
            Route::post('/get-detail-modal', ['as' => '.get_detail_modal', 'middleware' => ['permission:read-user'], 'uses' => 'UserDetailController@get_detail_modal']);
            Route::post('/get-permission-modal', ['as' => '.get_permission_modal', 'middleware' => ['permission:assign-user-permission'], 'uses' => 'UserDetailController@get_permission_modal']);
            Route::post('/role-change', ['as' => '.role_change', 'middleware' => ['permission:edit-role'], 'uses' => 'UserDetailController@role_change']);
            Route::post('/permissions-change', ['as' => '.permissions_change', 'middleware' => ['permission:assign-user-permission'], 'uses' => 'UserDetailController@permissions_change']);
        });


        Route::group(['prefix'=>'backup','as'=>'.backup'],function () {
            Route::get('/all',['as'=>'.all','middleware'=>['permission:backup'],'uses'=>'Controller@all_backup']);
            Route::get('/db',['as'=>'.db','middleware'=>['permission:backup'],'uses'=>'Controller@db_backup']);
            Route::get('/files',['as'=>'.files','middleware'=>['permission:backup'],'uses'=>'Controller@files_backup']);
        });
        Route::group(['prefix'=>'log','as'=>'.log'],function () {
            Route::get('/list',['as'=>'.index','middleware'=>['permission:read-log'],'uses'=>'LogController@index']);
            Route::post('/get-list', ['as' => '.get_index', 'middleware' => ['permission:read-log'], 'uses' => 'LogController@get_index']);
        });
        Route::group(['prefix'=>'role','as'=>'.role'],function () {
            Route::get('/list',['as'=>'.index','middleware'=>['permission:read-role'],'uses'=>'RolePermissionController@role_index']);
            Route::get('/add',['as'=>'.create','middleware'=>['permission:create-role'],'uses'=>'RolePermissionController@role_create']);
            Route::post('/store',['as'=>'.store','middleware'=>['permission:create-role'],'uses'=>'RolePermissionController@role_store']);
            Route::get('/edit/{role}',['as'=>'.edit','middleware'=>['permission:edit-role'],'uses'=>'RolePermissionController@role_edit']);
            Route::patch('/update/{role}',['as'=>'.update','middleware'=>['permission:edit-role'],'uses'=>'RolePermissionController@role_update']);
            Route::post('/get-list', ['as' => '.get_index', 'middleware' => ['permission:read-role'], 'uses' => 'RolePermissionController@get_role_index']);
        });
        Route::group(['prefix'=>'permission','as'=>'.permission'],function () {
            Route::get('/list',['as'=>'.index','middleware'=>['permission:read-permission'],'uses'=>'RolePermissionController@permission_index']);
            Route::get('/add',['as'=>'.create','middleware'=>['permission:create-permission'],'uses'=>'RolePermissionController@permission_create']);
            Route::post('/store',['as'=>'.store','middleware'=>['permission:create-permission'],'uses'=>'RolePermissionController@permission_store']);
            Route::get('/edit/{permission}',['as'=>'.edit','middleware'=>['permission:edit-permission'],'uses'=>'RolePermissionController@permission_edit']);
            Route::patch('/update/{permission}',['as'=>'.update','middleware'=>['permission:edit-permission'],'uses'=>'RolePermissionController@permission_update']);
            Route::post('/get-list', ['as' => '.get_index', 'middleware' => ['permission:read-permission'], 'uses' => 'RolePermissionController@get_permission_index']);
        });
        Route::group(['prefix'=>'user_tables_combination','as'=>'.user_tables_combination'],function () {
            Route::post('/get-user-tables-combination', ['as' => '.getCombination', 'middleware' => ['permission:read-user-tables-combination'], 'uses' => 'UserTablesCombinationController@getCombination']);
            Route::post('/set-user-tables-combination', ['as' => '.setCombination', 'middleware' => ['permission:read-user-tables-combination'], 'uses' => 'UserTablesCombinationController@setCombination']);
        });
        Route::group(['prefix'=>'lookup','as'=>'.lookup'],function () {
            Route::get('/list',['as'=>'.index','middleware'=>['permission:read-lookup'],'uses'=>'LookupController@index']);
            Route::get('/add',['as'=>'.create','middleware'=>['permission:create-lookup'],'uses'=>'LookupController@create']);
            Route::post('/store',['as'=>'.store','middleware'=>['permission:create-lookup'],'uses'=>'LookupController@store']);
            Route::get('/edit/{lookup}',['as'=>'.edit','middleware'=>['permission:edit-lookup'],'uses'=>'LookupController@edit']);
            Route::get('/get-child/{id}/{ref}',['as'=>'.get-child','uses'=>'LookupController@getChild']);
            Route::patch('/update/{lookup}',['as'=>'.update','middleware'=>['permission:edit-lookup'],'uses'=>'LookupController@update']);
            Route::post('/get-list', ['as' => '.get_index', 'middleware' => ['permission:read-lookup'], 'uses' => 'LookupController@get_index']);
            Route::delete('/{lookup}',['as'=>'.destroy','middleware'=>['permission:delete-lookup'],'uses'=>'LookupController@destroy']);
        });

    });


});
Route::post('check_unique_post',['uses'=>'CheckController@check_unique_post'])->name('check_unique_post');
Route::post('ckeditor_image_upload',['uses'=>'HomeController@ckeditor_image_upload'])->name('ckeditor_image_upload');

