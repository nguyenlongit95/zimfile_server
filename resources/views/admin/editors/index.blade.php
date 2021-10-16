@extends('admin.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="row">
        <div class="box">
                <div class="box-header">
                    <h3 class="box-title">List all editors</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @include('admin.layouts.alert')
                    <form action="{{ url('/admin/editors/search') }}" method="post">
                    <input class="fomr-control" name="_token" value="{{ csrf_token() }}" type="hidden">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>
                                Name:
                                <input class="fomr-control" name="name" type="text">
                            </th>
                            <th>
                                Email
                                <input class="fomr-control" name="email" type="email">
                            </th>
                            <th>
                                Address
                                <input class="fomr-control" name="address" type="text">
                            </th>
                            <th>
                                Phone
                                <input class="fomr-control" name="phone" type="text">
                            </th>
                            <th class="text-center">
                                <input class="btn btn-success" name="search" type="submit" value="Search">
                                <input class="btn btn-success" name="create" type="submit" value="Add New">
                            </th>
                        </tr>
                        </thead>
                    </table>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">List editors</h3>
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
                            <th>Phone</th>
                            <th>Password</th>
                            <th class="text-center">Assign Job</th>
                            <th class="text-center">Update</th>
                            <th class="text-center">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($editors as $editor)
                        <form action="{{ url('/admin/editors/edit/' . $editor->id) }}" method="post">
                        <input type="hidden" name="_token" class="form-control" value="{{ csrf_token() }}">
                        <tr>
                            <td>{{ $editor->id }}</td>
                            <td>
                            <input type="text" readonly name="name" class="form-control" value="{{ $editor->name }}">
                            </td>
                            <td>
                                <input readonly type="email" name="email" class="form-control" value="{{ $editor->email }}">
                            </td>
                            <td>
                                <input type="text" name="address" class="form-control" value="{{ $editor->address }}">
                            </td>
                            <td>
                                <input type="text" name="phone" class="form-control" value="{{ $editor->phone }}">
                            </td>
                            <td>
                                <input type="password" name="password" class="form-control" placeholder="Enter new password here!">
                            </td>
                            <td class="text-center">
                                <a @if($editor->assigned == true) disabled @else href="{{ url('/admin/editors/assign-job/' . $editor->id) }}" @endif class="btn btn-success">Assign Job</a>
                            </td>
                            <td class="text-center">
                                <input type="submit" class="btn btn-warning" name="update" value="update">
                            </td>
                            <td class="text-center"><a href="/admin/editors/delete/{{$editor->id}}" class="btn btn-danger padding510510">Delete</a></td>
                        </tr>
                        </form>
                        @endforeach
                        </tfoot>
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
