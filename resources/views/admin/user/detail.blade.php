@extends('admin.main')
@section('content')
    <!-- /.card-header -->
    <!-- form start -->
    <form action="{{ route('user.update', $user->id) }}" method="POST">
        <div class="card-body">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="email" name= "email" placeholder="Enter email" Value="{{ $user->email }}" readonly>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Name</label>
                <input type="text" class="form-control" id="name" name = "name" placeholder="Name" Value="{{ $user->name }}">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Phone</label>
                <input type="text" class="form-control" id="phone" name = "phone" placeholder="phone" Value="{{ $user->phone }}">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Address</label>
                <input type="text" class="form-control" id="address" name = "address" placeholder="Name" Value="{{ $user->address }}">
            </div>
            <div class="form-group">
                <label for="city" class="col-sm-2 col-form-label">City</label>
                    <select class="form-control" id="city" name="city" onchange="updateDistricts()">
                        <option value="" disabled {{ $user->city ? 'selected' : '' }}>Select your city</option>
                        <option value="Hà Nội" {{ $user->city === 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                        <option value="Hồ Chí Minh" {{ $user->city === 'Hồ Chí Minh' ? 'selected' : '' }}>Hồ Chí Minh</option>
                        <option value="Đà Nẵng" {{ $user->city === 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                        <option value="Huế" {{ $user->city === 'Huế' ? 'selected' : '' }}>Huế</option>
                        <option value="Cần Thơ" {{ $user->city === 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                        <option value="Hải Phòng" {{ $user->city === 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                        <option value="Quảng Ninh" {{ $user->city === 'Quảng Ninh' ? 'selected' : '' }}>Quảng Ninh</option>
                        <option value="Vũng Tàu" {{ $user->city === 'Vũng Tàu' ? 'selected' : '' }}>Vũng Tàu</option>
                        <option value="Đà Lạt" {{ $user->city === 'Đà Lạt' ? 'selected' : '' }}>Đà Lạt</option>
                    </select>
            </div>
        </div>
        <!-- /.card-body -->
        @csrf
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">Reset</button>
        </div>

    </form>
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('user.changePassword', $user->id) }}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordLabel">Đổi mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" name = "password" id="newPassword" required >
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" name = "password_confirmation" id="confirmPassword" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu mật khẩu mới</button>
                </div>
                @csrf
                </form>
            </div>
        </div>
    </div>
@endsection