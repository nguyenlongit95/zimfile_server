@extends('admin.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="row">

                @include('admin.layouts.alert')

                <div class="col-md-6">
                    <div class="row">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">List of users assigned to these editors in order of priority.</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <p>Priority list sorted in ascending order <span class="text-danger">*</span></p>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-center">#</td>
                                            <td>Name</td>
                                            <td>Email</td>
                                            <td class="text-center">Priority</td>
                                            <td class="text-center">Remove</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($listMyPriority))
                                            @foreach($listMyPriority as $user)
                                            <tr>
                                                <td class="text-center">{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td class="text-center">{{ $user->priority }}</td>
                                                <td class="text-center"><a href="{{ url('/admin/editors/priority/' . $id . '/remove/' . $user->id) }}"><i class="fa fa-trash"></i></a></td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List of unassigned users for these editors in order of priority.</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <p>Priority will be sorted in ascending order <span class="text-danger">*</span></p>
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Priority</td>
                                    <td class="text-center">Assign</td>
                                </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($listUserUnAssignPriority))
                                        @foreach($listUserUnAssignPriority as $user)
                                            <form action="{{ url('/admin/editors/priority/assign/' . $id) }}" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                                <tr>
                                                    <td>{{ $user->id }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <input type="number" class="form-control" name="priority" placeholder="1">
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn-none"><i class="fa fa-plus"></i></button>
                                                    </td>
                                                </tr>
                                            </form>
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
    </section>
    <!-- /.content -->

    <!-- Custom style css -->
    <style>
        .btn-none {
            background: white;
            border: none;
            color: #3c8dbc;
        }
    </style>
@endsection
