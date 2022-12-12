<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\RetailPrice;
use App\Models\Cart;
use DeepCopy\Filter\Filter;
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
            $invoice = new Invoice();
            $invoice->IDCus = $request->IDCus;
            $coupon = null;
            //check available of coupon if have coupon
            if ($request->CodeCoupon != null) {
                $coupon = Coupon::where('CodeCoupon', $request->CodeCoupon)->first();
                if ($coupon == null) return response()->json('Mã giảm giá không tồn tại', 404);
                else if ($coupon->Stock == 0 || $coupon->EndOn < now() || $coupon->StartOn > now()) return response()->json('Not available now (out of stock, expired..etc...', 400);
                else {
                    $coupon->Stock = $coupon->Stock - 1;
                    $invoice->IDCoupon = $coupon->IDCoupon;
                    $invoice->TotalValue = -$coupon->ValueDiscount;
                }
            }
            //check stock quantity of each product
            foreach ($request->InvoiceDetail as $product) {
                $productStock = Product::find($product['IDProduct'])->Stock;
                if ((int)((int)$productStock - (int)$product['Quantity']) == 0) return response()->json('Hết hàng', 404);
            }

            //create address
            $address = new Address();
            $address->City = $request->City;
            $address->District = $request->District;
            $address->Ward = $request->Ward;
            $address->AddressDetail = $request->AddressDetail;
            $address->Email = $request->Email;
            $address->Phone = $request->Phone;
            $address->FirstName = $request->FirstName;
            $address->LastName = $request->LastName;
            $address->save();
            //create a new invoice record
            $invoice->IDAddress = $address->IDAddress;
            $invoice->save();
            //create a list of invoiceDetail
            foreach ($request->InvoiceDetail as $product) {
                $invoiceDetail = DB::table('InvoiceDetail')->insert(
                    ['IDProduct' =>  $product['IDProduct'], 'Quantity' => $product['Quantity'], 'IDInvoice' => $invoice->IDInvoice]
                );
            }

            //check payment method and status

            //minus quantity of products
            $totalValue = 0;
            foreach ($request->InvoiceDetail as $product) {
                $productDetail = Product::find($product['IDProduct']);
                $productDetail->Stock = $productDetail->Stock - $product['Quantity'];
                $productDetail->save();
                $totalValue += (RetailPrice::showCurrent($product['IDProduct'])->Price ?? $productDetail->ListPrice) * $product['Quantity'];
            }

            //remove products out of customer cart
            Cart::where('IDCus', $request->IDCus)->delete();


            if ($coupon != null) $coupon->save();
            $invoice->TotalValue += $totalValue;
            $invoice->save();


            return response()->json($invoice, 200);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 404);
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
        if ($IDInvoice === null) {
            return response()->json(Invoice::all());
        }
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

    public function countInvoiceInRange($fromDate = null, $toDate = null, $statusTracking = null)
    {
        $invoices = Invoice::all();
        if (null != $fromDate) {
            $invoices = $invoices->where('CreateOn', '>=', $fromDate);
        }
        if (null != $toDate) {
            $invoices = $invoices->where('CreateOn', '<=', $toDate);
        }
        if (null != $statusTracking) {
            $invoices = $invoices->where('IDTracking', '<=', $statusTracking);
        }
        $count = $invoices->count();
        return response()->json($count, 200);
    }
}
