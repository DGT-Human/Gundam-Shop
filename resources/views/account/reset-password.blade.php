@extends('main')

@section('content')
    <div class="container" style="margin-top: 100px">
        <h2>Đặt Lại Mật Khẩu</h2>
        <form action="{{ route('reset.password') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu mới</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Đặt Lại Mật Khẩu</button>
        </form>
    </div>
    <div style="margin-top: 300px">

    </div>
@endsection
