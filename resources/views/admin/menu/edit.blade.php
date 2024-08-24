@extends('admin.main')

@section('head')
    <script src="/ckeditor/ckeditor.js"></script>
@endsection

@section('content')
    <form action="" method="post">
        <div class="card-body">
            <div class="form-group">
                <label for="menu">Tên danh mục</label>
                <input type="text" name="name" value=" {{ $menu->name }}" class="form-control" placeholder="Nhập tên danh mục">
            </div>

            <div class="form-group">
                <label>Danh mục</label>
                <select class="form-select" name = "parent_id" aria-label="Default select example">
                    <option value="0" {{ $menu->parent_id == 0 ? 'selected' : '' }}>Danh mục cha</option>
                    @foreach($menus as $menuParent)
                        <option value="{{ $menuParent->id }}" {{ $menu->parent_id == $menuParent->id ? 'selected' : '' }}>{{ $menuParent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="description" class="form-control" rows="3"> {{$menu->description }}</textarea>
            </div>

            <div class="form-group">
                <label>Mô tả chi tiết</label>
                <textarea name="content" id="content" class="form-control" rows="3"> {{$menu->content }}</textarea>
            </div>
            <div class="form-group">
                <label for="menu">Ảnh Menu</label>
                <input type="file" class="form-control" id="upload">
                <br>
                <div id="image_show">
                    <img src="{{ $menu->thumb }}" alt="" style="width: 100px">
                </div>
                <input type="hidden" name="thumb" id="thumb" value="{{ $menu->thumb }}">
            </div>
            <div class="form-group">
                <label>Kích hoạt</label>
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="radio" id="active" value="1" checked="" name="active" {{$menu ->active == 1 ? 'checked' : ''}}>
                    <label for="active" class="custom-control-label">Có</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="radio" id="no_active" value="0" name="active" {{$menu ->active == 0 ? 'checked' : ''}}>
                    <label for="no_active" class="custom-control-label">Không</label>
                </div>
            </div>

        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Sửa danh mục</button>
        </div>

        @csrf
    </form>
@endsection

@section('footer')
    <script>
        CKEDITOR.replace('content')
    </script>
@endSection
