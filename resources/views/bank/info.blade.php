<table class="table table-bordered" style="font-size: 15px;max-width: 800px;margin-left: auto;margin-right: auto;">
    <tbody>
    <tr class="" style="">
        <td class="text-right" style="text-align: right;">
            <strong style="color: black;">Ngân hàng</strong>
            <br>
        </td>
        <td class="text-left payment-instruction" style="text-align: left;">
            <div>
                <span style="color: black;">{{$bankName}}</span>
                <br>
            </div>
        </td>
    </tr>
    <tr class="" >
        <td class="text-right"  style="text-align: right;">
            <strong style="color: black;">Số tài khoản</strong>
            <br>
        </td>
        <td class="text-left payment-instruction" style="text-align: left;">
            <div>
                <span style="color: black;">{{$accountNumber}}</span>
                <br>
            </div>
        </td>
    </tr>
    <tr class="" style="background-color:#FBFBFB;">
        <td class="text-right"  style="text-align: right;">
            <strong style="color: black;">Chủ tài khoản</strong>
        </td>
        <td class="text-left payment-instruction" style="text-align: left;">
            <span style="color: black;">{{$accountName}}</span>
        </td>
    </tr>

    <tr class="" style="">
        <td class="text-right"  style="text-align: right;">
            <strong style="color: black;">Số tiền</strong>
            <br>
        </td>
        <td class="text-left payment-instruction" style="text-align: left;">
            <span style="color: black;">{!! $amount !!}</span>
        </td>
    </tr>
    <tr class="" >
        <td class="text-right" style="text-align: right;">
            <strong style="color: black;">Nội dung*:</strong>
        </td>
        <td class="text-left payment-instruction" style="text-align: left;">
            <strong style="font-size: 20px;">
                {{$content}}
            </strong>
        </td>
    </tr>
    </tbody>
</table>