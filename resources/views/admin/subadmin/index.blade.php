@extends('admin.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12 col-md-12 col-lg-12">
            @include('admin.layouts.alert')
            <div class="row">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">List sub admin</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th class="text-center">Update</th>
                                <th class="text-center">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($listSubAdmin))
                            @foreach($listSubAdmin as $admin)
                                <form action="{{ url('/admin/sub-admin/edit/' . $admin->id) }}" method="post">
                                    <input type="hidden" name="_token" class="form-control" value="{{ csrf_token() }}">
                                    <tr>
                                        <td>{{ $admin->id }}</td>
                                        <td>
                                            <input type="text" readonly name="name" class="form-control" value="{{ $admin->name }}">
                                        </td>
                                        <td>
                                            <input readonly type="email" name="email" class="form-control" value="{{ $admin->email }}">
                                        </td>
                                        <td>
                                            <input type="text" name="address" class="form-control" value="{{ $admin->address }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="submit" class="btn btn-warning" name="update" value="update">
                                        </td>
                                        <td class="text-center"><a href="/admin/sub-admin/delete/{{$admin->id}}" class="btn btn-danger padding510510">Delete</a></td>
                                    </tr>
                                </form>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                        {!! $editors->render() !!}
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
