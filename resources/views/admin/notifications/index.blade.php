@extends('admin.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12 col-md-12 col-lg-12">
            @include('admin.layouts.alert')
            <div class="row">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Create new Notifications</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="{{ url('/admin/notifications/create') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="col-md-5">
                                            <textarea name="notifications" id="create_notifications" class="form-control" cols="30" rows="2">Message content</textarea>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="status" id="status" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Deactivate</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <input class="btn btn-success" name="create" type="submit" value="Create">
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
                        <h3 class="box-title">List notifications</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="thead-style">
                            <tr>
                                <th>#</th>
                                <th>Notifications content</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($listNotifications))
                                @foreach($listNotifications as $notification)
                                    <form action="{{ url('/admin/notifications/edit/' . $notification->id) }}" method="post">
                                        <input type="hidden" name="_token" class="form-control" value="{{ csrf_token() }}">
                                        <tr>
                                            <td>{{ $notification->id }}</td>
                                            <td>
                                                <textarea type="text" name="notifications" class="form-control" rows="2">{{ $notification->notifications }}</textarea>
                                            </td>
                                            <td>
                                                <select name="status" id="status" class="form-control">
                                                    <option @if($notification->status == 1) selected @endif value="1">Active</option>
                                                    <option @if($notification->status == 0) selected @endif value="0">Deactivate</option>
                                                </select>
                                            <td class="text-center">
                                                <input type="submit" class="btn btn-warning" name="update" value="update">
                                                <a href="{{ url('/admin/notifications/delete/' . $notification->id) }}" class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {!! $listNotifications->appends($_GET)->links() !!}
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
