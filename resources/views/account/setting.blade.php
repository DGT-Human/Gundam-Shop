@extends('main')
@extends('admin.head')
@section('content')
    <div class="card card-outline">
        <div class="card-body box-profile">
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="/template/images/image.jpg" alt="User profile picture">
            </div>

            <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!-- About Me Box -->
        <div class="card-header">
            <h3 class="card-title">About Me</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <strong><i class="fas fa-book mr-1"></i>Email</strong>

            <p class="text-muted">
                {{ Auth::user()->email }}
            </p>

            <hr>

            <strong><i class="fas fa-map-marker-alt mr-1"></i> Phone</strong>

            <p class="text-muted">{{ Auth::user()->phone }}</p>

            <hr>

            <strong><i class="fas fa-pencil-alt mr-1"></i>Address</strong>

            <p class="text-muted">{{ Auth::user()->address }}</p>

            <hr>
        </div>
        <!-- /.card-body -->
        <div class="mt-3">
            <div class="container-fluid">
                @extends('admin.alert')
                <div class="row">
                    <div class="tab-pane active" id="settings">
                        <form class="form-horizontal" action=" {{ url('users/account/settings/'. Auth::user()->id) }}" method="POST">
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="Text" name="name" class="form-control" id="inputName" placeholder="Name" value="{{ Auth::user()->name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email" value="{{ Auth::user()->email }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                    <input type="Text" name="phone" class="form-control" id="inputName2" placeholder="Phone" value="{{ Auth::user()->phone }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputExperience" class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="inputExperience" placeholder="Experience"  name = "address">{{ Auth::user()->address }}</textarea>
                                </div>
                            </div>
                                <div class="form-group row">
                                    <label for="city" class="col-sm-2 col-form-label">City</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="city" name="city" onchange="updateDistricts()">
                                            <option value="" disabled {{ empty(Auth::user()->city) ? 'selected' : '' }}>Select your city</option>
                                            <option value="Hà Nội" {{ Auth::user()->city === 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                                            <option value="Hồ Chí Minh" {{ Auth::user()->city === 'Hồ Chí Minh' ? 'selected' : '' }}>Hồ Chí Minh</option>
                                            <option value="Đà Nẵng" {{ Auth::user()->city === 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                                            <option value="Huế" {{ Auth::user()->city === 'Huế' ? 'selected' : '' }}>Huế</option>
                                            <option value="Cần Thơ" {{ Auth::user()->city === 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                                            <option value="Hải Phòng" {{ Auth::user()->city === 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                                            <option value="Quảng Ninh" {{ Auth::user()->city === 'Quảng Ninh' ? 'selected' : '' }}>Quảng Ninh</option>
                                            <option value="Vũng Tàu" {{ Auth::user()->city === 'Vũng Tàu' ? 'selected' : '' }}>Vũng Tàu</option>
                                            <option value="Đà Lạt" {{ Auth::user()->city === 'Đà Lạt' ? 'selected' : '' }}>Đà Lạt</option>
                                        </select>
                                    </div>
                                </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">Change Password</button>
                                </div>
                            </div>
                            @csrf
                        </form>
                        <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="resetPasswordModalLabel">Change Password</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('setting.change-password', Auth::user()->id) }}" method="POST">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="currentPassword">Current Password</label>
                                                <input type="password" class="form-control" id="currentPassword" name="current_password" placeholder="Enter current password" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="newPassword">New Password</label>
                                                <input type="password" class="form-control" id="newPassword" name="new_password" placeholder="Enter new password" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="confirmPassword">Confirm New Password</label>
                                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm new password" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.card-body -->
        </div>
    <div class="mt-5">

    </div>
@endsection


