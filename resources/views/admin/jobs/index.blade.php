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
                                <select name="editor" id="search_editor" class="form-control">
                                    <option value="">---------</option>
                                    @if(!empty($editors))
                                        @foreach($editors as $editor)
                                            <option value="{{ $editor->id }}">{{ $editor->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </th>
                            <th>
                                Name users:
                                <select name="customer" id="search_customer" class="form-control">
                                    <option value="">---------</option>
                                    @if(!empty($customers))
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
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
                    <a href="{{ url('/admin/sub-admin/create-jobs') }}" class="pull-right btn btn-primary">Create job</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example2" class="table table-bordered bg-table-list-job">
                        <thead class="thead-style">
                        <tr>
                            <th style="width: 2%;">#</th>
                            <th class="text-center" style="width: 7%;">Date</th>
                            <th style="width: 18%;">Order name</th>
                            <th style="width: 5%;">Clients</th>
                            <th style="width: 8%;">Type</th>
                            <th style="width: 5%;">Editors</th>
                            <th style="width: 10%;" class="text-center">Status</th>
                            <th style="width: 21%;">Qc note</th>
                            <th style="width: 21%;">Client note</th>
                            <th style="width: 3%">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($jobs as $job)
                            <?php $carbon = new \Carbon\Carbon($job->created_at) ?>
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td class="text-center">{{ $carbon->format('d-m-Y') }}</td>
                                <td class="hover-nas font-weight-bold-list-job" onclick="showModalSelectEditor( {{ $job->id }} )">{{ $job->nas_dir }}</td>
                                <td>{{ $job->user_name }}</td>
                                <td>{{ $job->type_txt }}</td>
                                <td class="font-weight-bold-list-job">{{ $job->editor_name }}</td>
                                <!-- // 0: reject, 1 chưa assign, 2 đã assign, 3 confirm, 4 done -->
                                @if($job->status == 0)
                                    <td class="text-center font-weight-bold bg-danger">
                                        <span class="color-white">{{ $job->status_txt }}</span>
                                    </td>
                                @elseif($job->status == 1)
                                    <td class="text-center font-weight-bold">
                                        {{ $job->status_txt }}
                                    </td>
                                @elseif($job->status == 2)
                                    <td class="text-center font-weight-bold bg-green">
                                        <span class="color-white">{{ $job->status_txt }}</span>
                                    </td>
                                @elseif($job->status == 3)
                                    <td class="text-center font-weight-bold bg-warning">
                                        <span class="color-white">{{ $job->status_txt }}</span>
                                    </td>
                                @else
                                    <td class="text-center font-weight-bold bg-success">
                                        <span class="color-white">{{ $job->status_txt }}</span>
                                    </td>
                                @endif

{{--                                <td class="text-center font-weight-bold bg-danger">--}}
{{--                                    <!-- // 0: reject, 1 chưa assign, 2 đã assign, 3 confirm, 4 done -->--}}
{{--                                    @if($job->status == 0)--}}
{{--                                        <span class="text-danger">--}}
{{--                                    @elseif($job->status == 1)--}}
{{--                                        <span>--}}
{{--                                    @elseif($job->status == 2)--}}
{{--                                        <span class="text-green">--}}
{{--                                    @elseif($job->status == 3)--}}
{{--                                        <span class="text-warning">--}}
{{--                                    @else--}}
{{--                                        <span class="text-success text-bold">--}}
{{--                                    @endif--}}
{{--                                        {{ $job->status_txt }}--}}
{{--                                    </span>--}}
{{--                                </td>--}}
                                <td>{{ $job->note }}</td>
                                <td>{{ $job->customer_note }}</td>
                                <td class="text-center">
                                    <a href="{{ url('/admin/jobs/edit/' . $job->id) }}"><i class="fa fa-pencil"></i></a>
                                    @if($job->status == 1)
                                    |
                                    <a href="{{ url('/admin/jobs/delete/' . $job->id) }}"><i class="fa fa-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
{{--                {!! $jobs->render() !!}--}}
                {!! $jobs->appends($_GET)->links() !!}
            </div>
            <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" id="modal-dialog">
            <form action="{{ url('/admin/jobs/assign-job-p') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="job_id" value="" id="modal_input_job_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Choose an editor</h5>
                    </div>
                    <div class="modal-body" id="list-editor">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .fade {
            opacity: 1;
        }
        #modal-dialog {
            margin-top: 10%;
        }
        .hover-nas:hover {
            cursor: pointer;
            color: #0b93d5;
        }
        .bg-table-list-job {
            background: #00000015;
        }
        .bg-green {
            background: #00a65a;
        }
        .bg-success {
            background: #3c763d;
        }
        .bg-danger {
            background: #a94442;
        }
        .bg-warning {
            background: #f39c12eb;
        }
        .font-weight-bold-list-job {
            font-weight: bold !important;
        }
        .color-white {
            color: white !important;
        }
    </style>
@endsection

@section('custom-js')
    <script>
        /**
         * Function show modal and select editor assign jobs
         *
         * @param jobId
         */
        function showModalSelectEditor(jobId) {
            // Call API and show html to content
            $('#exampleModal').show();
            $('#modal_input_job_id').val(jobId);
            $.ajax({
                url: '{{ url('/admin/jobs/assign-job-mn') }}',
                method: 'GET',
                data: {
                    jobId: jobId
                },
                success: function (response) {
                    if (response !== null) {
                        $('#list-editor').html(response);
                    } else {
                        $('#list-editor').html('<p class="text-danger">Error system</p>');
                    }
                },
            })
        }

        /**
         * Function close modal
         */
        function closeModal() {
            $('#exampleModal').hide();
        }
    </script>
@endsection
