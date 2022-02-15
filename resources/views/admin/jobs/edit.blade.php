@extends('admin.master')

@section('content')
    <section class="content">

        <!-- SELECT2 EXAMPLE -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Update job: {{ $job->nas_dir }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @include('admin.layouts.alert')
                <div class="row">
                    <form action="admin/jobs/update/{{ $job->id }}" method="POST" enctype="multipart/form-data">
                    <div class="col-md-6">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box box-danger">
                            <div class="box-body">
                                <!-- Date mm/dd/yyyy -->
                                <div class="form-group">
                                    <label for="">Job name</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-edit fa-pen-alt"></i>
                                        </div>
                                        <input type="text" disabled name="name" class="form-control" value="{{ $job->nas_dir }}">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->

                                <!-- phone mask -->
                                <div class="form-group">
                                    <label>Clients</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user-circle"></i>
                                        </div>
                                        <input type="email" disabled name="email" class="form-control" value="{{ $job->customer_txt }}">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->

                                <!-- phone mask -->
                                <div class="form-group">
                                    <label>Editors</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input type="text" disabled name="address" class="form-control" value="{{ $job->editor_txt }}">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->

                                <!-- phone mask -->
                                <div class="form-group">
                                    <label>Status</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-line-chart"></i>
                                        </div>
                                        <!-- 0: reject, 1 chưa assign, 2 đã assign, 3 confirm, 4 done -->
                                        <select name="status" disabled class="form-control" id="">
                                            <option @if($job->type == 0) selected @endif value="0">Reject</option>
                                            <option @if($job->type == 1) selected @endif value="1">No Assign</option>
                                            <option @if($job->type == 2) selected @endif value="2">Assigned</option>
                                            <option @if($job->type == 3) selected @endif value="3">Confirm</option>
                                            <option @if($job->type == 4) selected @endif value="4">Done</option>
                                        </select>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->

                                <!-- phone mask -->
                                <div class="form-group">
                                    <label>Created date</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input type="text" disabled name="address" class="form-control" value="{{ $job->created_at }}">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                                <p>- You cannot edit the information on this side <span class="text-danger">*</span></p>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>

                    <div class="col-md-6">
                        <!-- phone mask -->
                        <div class="form-group">
                            <label>Type</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-edit"></i>
                                </div>
                                <!-- 1: Photo editing	2: Day to dusk	3: Virtual Staging	4: Additional Retouching -->
                                <select name="type" class="form-control" id="">
                                    <option @if($job->type == 1) selected @endif value="1">Photo editing</option>
                                    <option @if($job->type == 2) selected @endif value="2">Day to dusk</option>
                                    <option @if($job->type == 3) selected @endif value="3">Virtual Staging</option>
                                    <option @if($job->type == 4) selected @endif value="4">Additional Retouching</option>
                                </select>
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
                        <!-- phone mask -->
                        <div class="form-group">
                            <label>Qc note</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <textarea name="note" class="form-control" id="qc_note" cols="30" rows="4">{{ $job->note }}</textarea>
                            </div>
                            <!-- /.input group -->
                        </div>

                        <!-- phone mask -->
                        <div class="form-group">
                            <label>Customer note</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <textarea name="customer_note" class="form-control" id="customer_note" cols="30" rows="5">{{ $job->customer_note }}</textarea>
                            </div>
                            <!-- /.input group -->
                        </div>

                        <!-- IP mask -->
                        <div class="form-group">
                            <label>Submit data:</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-paper-plane"></i>
                                </div>
                                <input type="submit" class="form-control btn-success" value="Submit">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
                    </div>
                    </form>
                </div>
            </div>
            <!-- /.col (right) -->
        </div>
        <!-- /.row -->
        <!-- /.content -->
        </div>
    </section>
@endsection
