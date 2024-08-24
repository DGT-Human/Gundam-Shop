@extends('admin.main')
@section('content')
    <table class='table'>
        <thead>
        <tr>
            <th style="width: 50px">ID</th>
            <th>Name</th>
            <th>Link</th>
            <th>Picture</th>
            <th>Active</th>
            <th>Update</th>
            <th style="width:100px">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sliders as $key => $slider)
            <tr>
                <th>{{ $slider->id }}</th>
                <th>{{ $slider->name }}</th>
                <th>{{ $slider->url}}</th>
                <th><a href="{{ $slider->thumb }}" target="_blank"><img src="{{ $slider->thumb }}" width="100px"></a></th>
                <th>{!!\App\Helpers\helper::active($slider->active) !!}</th>
                <th>{{ $slider->updated_at }}</th>
                <th>
                    <a href="/admin/sliders/edit/{{ $slider->id }}" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" onclick="removeRow({{ $slider->id }}, '/admin/sliders/destroy')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                </th>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! $sliders->links() !!}
@endsection

