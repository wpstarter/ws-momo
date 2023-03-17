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
        </form>
    @else
        <p>Chưa kết nối momo</p>
    @endif
@endsection