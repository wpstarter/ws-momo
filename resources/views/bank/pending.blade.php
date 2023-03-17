<section class="woocommerce-bacs-bank-details">
    <h2 class="wc-bacs-bank-details-heading">{{esc_html__( 'Thông tin chuyển khoản', 'woocommerce' )}}</h2>
    <p style="text-align: center;"><img src="{{$qrUrl}}" style="max-width: 300px"/></p>
    @include('bank.info')
    <strong style="color:red;">Quý khách chuyển khoản và điền chính xác nội dung thanh toán và số tiền để được kích hoạt tự động trong 2 phút</strong>
    <p>&nbsp;</p>
</section>