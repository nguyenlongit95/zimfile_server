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
                                <h3 class="box-title">List of jobs of this editor: {{ $editor->name }}</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-bordered table-hover text-center">
                                    <thead class="thead-style">
                                    <td>#</td>
                                    <td>Customer name</td>
                                    <td>Customer email</td>
                                    <td>Job name</td>
                                    <td>Action</td>
                                    </thead>
                                    <tbody>
                                    @if(!empty($listJobOfEditor))
                                        @foreach($listJobOfEditor as $job)
                                        <tr>
                                            <td>{{ $job->id }}</td>
                                            <td>{{ $job->customer_name }}</td>
                                            <td>{{ $job->email }}</td>
                                            <td>{{ $job->nas_dir }}</td>
                                            <td>
                                                <a href="{{ url('/admin/editors/' . $job->id . '/remove-manual-assign') }}"><i class="fa fa-trash"></i></a>
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
                                <h3 class="box-title">List of jobs that have not been assigned to any editors.</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-bordered table-hover text-center">
                                    <thead class="thead-style">
                                    <td>#</td>
                                    <td>Customer name</td>
                                    <td>Customer email</td>
                                    <td>Job name</td>
                                    <td>Assign</td>
                                    </thead>
                                    <tbody>
                                    @if(!empty($listJobNotEditor))
                                        @foreach($listJobNotEditor as $job)
                                            <tr>
                                                <td>{{ $job->id }}</td>
                                                <td>{{ $job->customer_name }}</td>
                                                <td>{{ $job->email }}</td>
                                                <td>{{ $job->nas_dir }}</td>
                                                <td>
                                                    <a href="{{ url('/admin/editors/' . $job->id . '/manual-assign/' . $editor->id) }}"><i class="fa fa-paperclip"></i></a>
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
