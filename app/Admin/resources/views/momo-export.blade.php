@extends('admin::layout')
@section('content')
    <h3>Xuất dữ liệu</h3>
    <label for="export_data">Hãy copy thông tin bên dưới để nhập vào trang web khác</label>
    <textarea id="export_data" name="export_data" style="width: 100%" rows="10">{{$export_data}}</textarea>
    <p class="error-message">Lưu ý: Dữ liệu này chứa thông tin nhạy cảm, không chia sẻ với bất kỳ ai!</p>
@endsection