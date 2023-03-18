@extends('admin::layout')
@section('content')
    <h3>Thông tin tài khoản</h3>
    @if($phone)
        <table>
            <tr>
                <td>Số điện thoại</td>
                <td>{{$phone}}</td>
            </tr>
            <tr>
                <td>Tên tài khoản</td>
                <td>{{$name}}</td>
            </tr>
            <tr>
                <td>Số dư</td>
                <td>{!! $balance !!}</td>
            </tr>
            <tr>
                <td>Cập nhật</td>
                <td>{{$updated_at}}</td>
            </tr>
        </table>
        <form method="POST">
            @csrf
            <button name="action" value="update" class="button button-primary">Cập nhật</button>
            <button name="action" value="disconnect" class="button button-primary">Ngắt kết nối</button>
            <button name="action" value="export" class="button button-primary">Xuất dữ liệu</button>
        </form>
    @else
        <h3>Chưa kết nối momo</h3>
        <form method="POST">
            @csrf
            <label for="import_data">Nhập thông tin kết nối (copy từ lệnh <mark>php artisan momo export</mark>)</label>
            <textarea id="import_data" name="import_data" style="width: 100%" rows="10">{{ws_old('import_data')}}</textarea>
            @error('import_data')
                <p class="error-message">{{$message}}</p>
            @enderror
            <button name="action" value="import" class="button button-primary">Nhập</button>
        </form>
    @endif
@endsection