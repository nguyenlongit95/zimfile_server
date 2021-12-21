@extends('admin.master')

@section('content')
    <section class="content">

        <!-- SELECT2 EXAMPLE -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Group: <i>{{ $group->group_name }}</i></h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @include('admin.layouts.alert')
                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-danger">
                            <div class="box-header">
                                <h3 class="box-title">List of users assigned to this group: {{ $group->group_name }}</h3>
                            </div>
                            <div class="box-body">
                            <table class="table table-bordered table-hover text-center">
                                <thead>
                                   <td>#</td>
                                   <td>Name</td>
                                   <td>Email</td>
                                   <td>Actions</td>
                                </thead>
                                <tbody>
                                @if(!empty($listUserInGroup))
                                    @foreach ($listUserInGroup as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <a href="{{ url('/admin/groups/' . $group->id . '/remove-customer/' . $user->id . '/') }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>

                    <div class="col-md-6">
                        <div class="box box-danger">
                            <div class="box-header">
                                <h3 class="box-title">List of users not assigned to any group</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-bordered table-hover text-center">
                                    <thead>
                                    <td>#</td>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Actions</td>
                                    </thead>
                                    <tbody>
                                    @if(!empty($listUserFreeGroup))
                                        @foreach ($listUserFreeGroup as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <a href="{{ url('/admin/groups/' . $group->id . '/assign-customer/' . $user->id . '/') }}"><i class="fa fa-plus"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                </div>
            </div>
            <!-- /.col (right) -->
        </div>
        <!-- /.row -->
        <!-- /.content -->
        </div>
    </section>
@endsection
