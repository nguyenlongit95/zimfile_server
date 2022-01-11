@extends('admin.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="row">
        <div class="box">
                <div class="box-header">
                    <h3 class="box-title">List all jobs</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @include('admin.layouts.alert')
                    <form action="{{ url('/admin/jobs/search') }}" method="get">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>
                                Name Editors:
                                <input class="form-control" name="name_editor" type="text">
                            </th>
                            <th>
                                Name users:
                                <input class="form-control" name="name_user" type="text">
                            </th>
                            <th>
                                Date create jobs:
                                <input class="form-control" type="date" name="date" placeholder="">
                            </th>
                            <th>
                                Type:
                                <select name="type" class="form-control" id="type">
                                    <option value="">-----</option>
                                    <option value="1">Photo editing</option>
                                    <option value="2">Day to dusk</option>
                                    <option value="3">Virtual Staging</option>
                                    <option value="4">Additional Retouching</option>
                                </select>
                            </th>
                            <th>
                                Status:
                                <select name="status" class="form-control" id="status">
                                    <option value="">-----</option>
                                    <option value="0">Reject</option>
                                    <option value="1">None assign</option>
                                    <option value="2">Assigned</option>
                                    <option value="3">Confirm</option>
                                    <option value="4">Done</option>
                                </select>
                            </th>
                            <th class="text-center">
                                <input class="btn btn-success" name="search" type="submit" value="Search">
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
                    <h3 class="box-title">Alls jobs</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example2" class="table">
                        <thead>
                        <tr>
                            <th style="width: 2%;">#</th>
                            <th style="width: 10%;">Date</th>
                            <th style="width: 15%;">Order name</th>
                            <th style="width: 5%;">Clients</th>
                            <th style="width: 8%;">Type</th>
                            <th style="width: 5%;">Editors</th>
                            <th style="width: 10%;" class="text-center">Status</th>
                            <th style="width: 15%;">Qc note</th>
                            <th style="width: 30%;">Client note</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($jobs as $job)
                            <?php $carbon = new \Carbon\Carbon($job->created_at) ?>
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>{{ $carbon->format('d-m-Y') }}</td>
                                <td>{{ $job->nas_dir }}</td>
                                <td>{{ $job->user_name }}</td>
                                <td>{{ $job->type_txt }}</td>
                                <td>{{ $job->editor_name }}</td>
                                <td class="text-center">
                                    <!-- // 0: reject, 1 chưa assign, 2 đã asign, 3 confirm, 4 done -->
                                    @if($job->status == 0)
                                        <span class="text-danger">
                                    @elseif($job->status == 1)
                                        <span>
                                    @elseif($job->status == 2)
                                        <span class="text-green">
                                    @elseif($job->status == 3)
                                        <span class="text-warning">
                                    @else
                                        <span class="text-success text-bold">
                                    @endif
                                        {{ $job->status_txt }}
                                    </span>
                                </td>
                                <td>{{ $job->note }}</td>
                                <td>{{ $job->customer_note }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $jobs->render() !!}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
