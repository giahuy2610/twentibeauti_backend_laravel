<div class="emailInvoice" style="padding: 0px 20px;">
<div class="myBrand" style="text-align:center; display:flex; flex-direction:column; position:absolute">
<img src="https://i.imgur.com/Z59Pfzd.png" alt="logo" style="width:10%; "/>
<h1>TWENTI</h1>
</div>
<p style="color:#333">Xin chào {{$address->FirstName}}</p>
<p>Đơn hàng SS{{$invoice->IDInvoice}} của bạn đã được đặt thành công</p>
<hr>
<h3>THÔNG TIN ĐƠN HÀNG</h3>
<div>
<div class="info" style="display:flex; flex-direction:column;width:70%" >
<p style="width:50%">Mã đơn hàng:</p>
<p style="width:50%">SS{{$invoice->IDInvoice}}</p>
</div>
<div class="info" style="display:flex; flex-direction:column;width:70%" >
<p style="width:50%">Tổng giá trị đơn hàng là: </p>
<p style="width:50%">{{$totalValue}}</p>
</div>
<h4>Cảm ơn bạn đã mua sắm tại hệ thống cửa hàng của chúng tôi.</h4>
</div>
</div>
