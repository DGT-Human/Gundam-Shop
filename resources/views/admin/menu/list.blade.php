@extends('admin.main')
@section('content')
<table class='table'>
        <thead>
            <tr>
                <th style="width: 50px">ID</th>
                <th>Name</th>
                <th>Active</th>
                <th>Update</th>
                <th style="width:100px">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            {!! \app\Helpers\helper::menus($menus) !!}
        </tbody>
</table>
@endsection
