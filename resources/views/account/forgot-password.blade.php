@extends('main')

@section('content')
    <div class="container" style="margin-top: 100px">
        <h2>Quên Mật Khẩu</h2>
        <form action="{{ route('forgot.password') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Xác Nhận</button>
        </form>
    </div>
    <div style="margin-top: 300px">

    </div>
@endsection

