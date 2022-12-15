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
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
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
            $invoice->MethodPay = $request->MethodPay;
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

            //minus quantity of products
            $totalValue = 0;
            foreach ($request->InvoiceDetail as $product) {
                $productDetail = Product::find($product['IDProduct']);
                $productDetail->Stock = $productDetail->Stock - $product['Quantity'];
                $productDetail->save();
                $totalValue += (RetailPrice::showCurrent($product['IDProduct'])->Price ?? $productDetail->ListPrice) * $product['Quantity'];
            }
            //Send email confirm order
            Mail::send('email.CheckInvoice',compact('address','invoice','totalValue','productDetail'), function($email) use($address){
                $email->subject('TWENTI - XÁC NHẬN ĐƠN HÀNG');
                $email->to($address->Email, $address->FirstName);
            });
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

    public function changeTracking(Request $request)
    {
        $invoice = Invoice::find($request->IDInvoice);
        if ($invoice == null) return response()->json($request->IDInvoice, 404);
        $invoice->IDTracking = $request->IDTracking;
        $invoice->save();
        return response()->json($invoice);
    }

    public function pay(Request $request)
    {

        /**
         * Description of vnpay_ajax
         *
         * @author xonv
         */
        $vnp_TmnCode = "YWI14QQC"; //Website ID in VNPAY System
        $vnp_HashSecret = "CYFLWMNCJNGRGTFGLHZBUZTWYUHFTPLU"; //Secret key
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:8000/api/vnpay-return";
        $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
        //Config input format
        //Expire
        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

        $vnp_TxnRef = $request->IDInvoice; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toán đơn hàng ';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $request->TotalValue * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version
        $vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
        //Billing
        $vnp_Bill_Mobile = '0934998386';
        $vnp_Bill_Email = "xonv@vnpay.vn";
        $fullName = trim("NGUYEN VAN XO");
        if (isset($fullName) && trim($fullName) != '') {
            $name = explode(' ', $fullName);
            $vnp_Bill_FirstName = array_shift($name);
            $vnp_Bill_LastName = array_pop($name);
        }
        $vnp_Bill_Address = "22 Lang Ha";
        $vnp_Bill_City = "Hà Nội";
        $vnp_Bill_Country = 'VN';
        $vnp_Bill_State = '';
        // Invoice
        $vnp_Inv_Phone = "02437764668";
        $vnp_Inv_Email = "pholv@vnpay.vn";
        $vnp_Inv_Customer = "Lê Văn Phổ";
        $vnp_Inv_Address = "22 Láng Hạ, Phường Láng Hạ, Quận Đống Đa, TP Hà Nội";
        $vnp_Inv_Company = "Công ty Cổ phần giải pháp Thanh toán Việt Nam";
        $vnp_Inv_Taxcode = "0102182292";
        $vnp_Inv_Type = "I";
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
            "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
            "vnp_Bill_Email" => $vnp_Bill_Email,
            "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
            "vnp_Bill_LastName" => $vnp_Bill_LastName,
            "vnp_Bill_Address" => $vnp_Bill_Address,
            "vnp_Bill_City" => $vnp_Bill_City,
            "vnp_Bill_Country" => $vnp_Bill_Country,
            "vnp_Inv_Phone" => $vnp_Inv_Phone,
            "vnp_Inv_Email" => $vnp_Inv_Email,
            "vnp_Inv_Customer" => $vnp_Inv_Customer,
            "vnp_Inv_Address" => $vnp_Inv_Address,
            "vnp_Inv_Company" => $vnp_Inv_Company,
            "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
            "vnp_Inv_Type" => $vnp_Inv_Type
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
        // if (isset($_POST['redirect'])) {
        //     header('Location: ' . $vnp_Url);
        //     die();
        // } else {
        //     echo json_encode($returnData);
        // }
        header('Location: ' . $vnp_Url);
        die();
    }

    public function vnpayReturn(Request $request)
    {
        if ($request->vnp_ResponseCode == '00') {
            $invoice = Invoice::find($request->vnp_TxnRef);
            $invoice->IsPaid = true;
            $invoice->save();
        }
        return Redirect::to('http://localhost:3000/details/' .$request->vnp_TxnRef);
    }
}
