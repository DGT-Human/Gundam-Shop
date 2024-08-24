@extends('admin.main')

@section('head')
    <script src="/ckeditor/ckeditor.js"></script>
@endsection

@section('content')
    <form action="" method="post">
      <div class="card-body">
        <div class="form-group">
          <label for="menu">Tên danh mục</label>
          <input type="text" name="name" class="form-control" placeholder="Nhập tên danh mục">
        </div>

        <div class="form-group">
            <label>Danh mục</label>
            <select class="form-select" name = "parent_id" aria-label="Default select example">
                <option value="0">Danh mục cha</option>
                @foreach($menus as $menu)
                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label>Mô tả chi tiết</label>
            <textarea name="content" id="content" class="form-control" rows="3"></textarea>
        </div>
          <div class="form-group">
              <label for="menu">Ảnh Menu</label>
              <input type="file" class="form-control" id="upload">
              <br>
              <div id="image_show">

              </div>
              <input type="hidden" name="thumb" id="thumb">
          </div>
        <div class="form-group">
            <label>Kích hoạt</label>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="radio" id="active" value="1" checked="" name="active">
                <label for="active" class="custom-control-label">Có</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="radio" id="no_active" value="0" name="active">
                <label for="no_active" class="custom-control-label">Không</label>
            </div>
        </div>

      </div>
      <!-- /.card-body -->

      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Tạo danh mục</button>
      </div>

      @csrf
    </form>
@endsection

@section('footer')
<script>
    CKEDITOR.replace('content')
</script>
@endSection
