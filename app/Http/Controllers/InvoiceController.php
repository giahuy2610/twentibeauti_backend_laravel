<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Models\Address;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            //check available of coupon if have coupon
            //if have coupon is used, minus stock quantity of coupon
            //check payment method and status
            //check stock quantity of each product
            //minus quantity of products
            //remove products out of customer cart
            //create a new invoice record
            //create a list of invoiceDetail
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($IDInvoice =  null)
    {
        $invoice = Invoice::find($IDInvoice);
        if ($invoice == null) return response()->json('Invoice is not exist', 400);
        $products = DB::table("InvoiceDetail")->where("IDInvoice", $IDInvoice)->get();
        $productArr = [];
        foreach ($products as $product) {
            $productDetail = Product::getProductDetailByID($product->IDProduct);
            $productDetail->Quantity = $product->Quantity;
            array_push($productArr, $productDetail);
        }
        $invoice->Products = $productArr;
        $invoice->Address = Address::find($invoice->IDAddress);
        $invoice->Coupon = Coupon::find($invoice->IDCoupon);
        return response()->json($invoice);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    public function invoiceOfCustomer(int $IDCustomer)
    {
        $data = [];
        $invoices = Invoice::where('IDCus', $IDCustomer)->get();
        foreach ($invoices as $invoice) {
            $invoice->Address = Address::find($invoice->IDAddress);
            array_push($data, $invoice);
        }
        return response()->json($data, 200);
    }
}
