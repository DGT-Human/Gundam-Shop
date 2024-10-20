@extends('admin.head')
<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
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
        <div class="card card-primary">
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
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link" href="/" data-toggle="tab">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('users/account/'. Auth::user()->id) }}" data-toggle="tab">Information</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url('users/account/settings/'. Auth::user()->id) }}" data-toggle="tab">Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-toggle="tab">Logout</a></li>
                </ul>
            </div><!-- /.card-header -->

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <div class="container-fluid">
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
                                    <input type="phone" name="phone" class="form-control" id="inputName2" placeholder="Phone" value="{{ Auth::user()->phone }}">
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
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                            @csrf
                        </form>
                    </div>
                </div>
            </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <!-- /.col -->
</div>
