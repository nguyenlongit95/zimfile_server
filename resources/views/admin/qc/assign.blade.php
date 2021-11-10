@extends('admin.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">QC user management details</h3>
                    </div>
                    @include('admin.layouts.alert')
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <h4>List user's belongs QC: <span class="text-danger font-weight-bold">{{ $qc->name }}</span></h4>
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th class="text-center">Remove</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($userBelongMe as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td class="text-center">
                                                <a href="/admin/qc/remove-belong/{{$user->id}}" class="text-danger padding510510"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4 class="pull-left">List of users that have not been assigned to any qc</h4>
                                <form action="{{ url('/admin/qc/assign-user/' . $qc->id) }}" method="POST">
                                <input type="submit" name="assign" value="Assign" class="btn btn-primary pull-right">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th class="text-center">Assign</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($userNotAssign as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" name="user_id[]" value="{{ $user->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
