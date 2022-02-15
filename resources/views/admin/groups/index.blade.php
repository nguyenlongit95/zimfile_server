@extends('admin.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="row">
                @include('admin.layouts.alert')
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Create new group</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="{{ url('/admin/groups/create') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="col-md-2">
                                            <input class="form-control" id="group_name" name="group_name" type="text" placeholder="Name of group.">
                                        </div>
                                        <div class="col-md-1">
                                            <input class="btn btn-success" name="search" type="submit" value="Create">
                                        </div>
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
                        <h3 class="box-title">All groups</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="thead-style">
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Group name</th>
                                    <th class="text-center">Assign</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($groups))
                                @foreach($groups as $group)
                                <form action="{{ url('/admin/groups/' . $group->id . '/edit') }}" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <tr>
                                    <th class="margin-top-5">{{ $group->id }}</th>
                                    <th class="text-center">
                                        <input type="text" name="group_name" class="form-control" value="{{ $group->group_name }}">
                                    </th>
                                    <th class="text-center">
                                        <a class="btn btn-primary" href="{{ url('/admin/groups/' . $group->id . '/assign-customers') }}">Assign customers</a>
                                    </th>
                                    <th class="text-center">
                                        <button type="submit" class="btn-none"><i class="fa fa-pencil"></i></button>| &nbsp;
                                        <a href="{{ url('/admin/groups/' . $group->id . '/delete') }}"> <i class="fa fa-trash"></i></a>
                                    </th>
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
    </section>
    <!-- /.content -->

    <style>
        .btn-none {
            border: none;
            background: none;
            color: #3c8dbc;
        }
        .margin-top-10 {
            margin-top: 10px;
        }
        .margin-top-5 {
            margin-top: 5px;
        }
    </style>
@endsection
