@extends('admin.master')

@section('content')
    <section class="content">

        <!-- SELECT2 EXAMPLE -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Editor: <i>{{ $editor->name }}</i></h3>

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
                                <h3 class="box-title">List of groups assigned to this editor: {{ $editor->name }}</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-bordered table-hover text-center">
                                    <thead>
                                    <td>#</td>
                                    <td>Group name</td>
                                    <td>Actions</td>
                                    </thead>
                                    <tbody>
                                    @if(!empty($listGroupsForMe))
                                        @foreach ($listGroupsForMe as $group)
                                            <tr>
                                                <td>{{ $group->id }}</td>
                                                <td>{{ $group->group_name }}</td>
                                                <td>
                                                    <a href="{{ url('/admin/editors/' . $group->id . '/remove-group/') }}"><i class="fa fa-trash"></i></a>
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
                                <h3 class="box-title">The list of groups has not been assigned to this editor</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-bordered table-hover text-center">
                                    <thead>
                                    <td>#</td>
                                    <td>Group name</td>
                                    <td>Actions</td>
                                    </thead>
                                    <tbody>
                                    @if(!empty($listGroupsNotForMe))
                                        @foreach ($listGroupsNotForMe as $group)
                                            <tr>
                                                <td>{{ $group->id }}</td>
                                                <td>{{ $group->group_name }}</td>
                                                <td>
                                                    <a href="{{ url('/admin/editors/' . $editor->id . '/assign-group/' . $group->id . '/') }}"><i class="fa fa-plus"></i></a>
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
