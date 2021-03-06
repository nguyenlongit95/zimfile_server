@extends('admin.master')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Create jobs for users</h4>
                    </div>

                    <!-- step 1 -->
                    <div class="col-md-3">
                        <div class="box height350">
                            <div class="box-header">
                                <h3 class="box-title">Step 1: select user</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body height-box-body" id="box-select-customer">
                                <div id="content-select-customer">
                                    @if(!empty($listUsers))
                                        @foreach($listUsers as $user)
                                        <input type="radio" id="user_{{ $user->id }}" name="users" class="users" value="{{ $user->id }}">
                                            <label class="font-size-13" for="user_{{ $user->id }}">{{ $user->name }} - </label>({{ $user->email }})<br/>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button class="btn btn-primary pull-right" onclick="nextStep2()">Next step <i class="fa fa-arrow-right"></i> </button>
                            </div>
                        </div>
                    </div>
                    <!-- end step 1 -->

                    <!-- step 2 -->
                    <div class="col-md-3 hidden" id="step-2">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Step 2: Check and create folders by date</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body height-box-body">
                                <input type="hidden" id="id_main_folder" value="">
                                <h3>Today's Directory: <span class="text-danger" id="main_folder_name"></span></h3>
                                <p class="text-danger" id="txt-warning-step-2"></p>
                                <button class="btn btn-warning pull-right hidden" id="btn-create-folder" onclick="createMainFolder()">Create folder</button>
                                <img src="{{ asset('loading.gif') }}" id="loading-btn-crate-folder" class="img-loading pull-right hidden" alt="">
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer pull-right">
                                <div class="row">
                                    <button class="btn btn-primary" onclick="nextStep3()">Create single job <i class="fa fa-arrow-right"></i> </button>
                                    |
                                    <button class="btn btn-primary" onclick="nextStep3MultipleJobs()">Create multiple job <i class="fa fa-arrow-right"></i> </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end step 2 -->

                    <!-- Step 3 create single jobs -->
                    <div class="col-md-3 hidden" id="step-3">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Step 3: Create jobs</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body height-box-body">
                                <div class="form-group">
                                    <label for="">Name of folder <span class="text-danger">*</span></label>
                                    <input type="text" id="name_job" value="" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Type of folder <span class="text-danger">*</span></label>
                                    <!--
                                     -- 1: Photo editing 2: Day to dusk	3: Virtual Staging	4: Additional Retouching
                                     -->
                                    <select name="type" id="type_job" class="form-control">
                                        <option value="1">Photo editing</option>
                                        <option value="2">Day to dusk</option>
                                        <option value="3">Virtual Staging</option>
                                        <option value="4">Additional Retouching</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="note">Instruction <span class="text-danger">*</span></label>
                                    <textarea name="note" class="form-control" id="note_create" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button class="btn btn-primary pull-right" onclick="nextStep4()">Create job <i class="fa fa-plus-square"></i> </button>
                                <img src="{{ asset('loading.gif') }}" id="loading-btn-crate-job" class="img-loading pull-right hidden" alt="">
                            </div>
                        </div>
                    </div>
                    <!-- end step 3 -->

                    <!-- step 3 create multi jobs -->
                    <div class="col-md-3 hidden" id="step-3-multi-jobs">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Step 3: Create multiple jobs</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group">
                                    <p>Number of jobs to be created: <span id="number-job-to-be-created">1</span></p>
                                </div>
                                <div class="form-group" id="name_job_multiple">
                                    <label for="">Name of folder <span class="text-danger">*</span></label>
                                    <input type="text" name="name_job_1" id="input_name_job_1" value="" class="form-control btn_remove_job_a">
                                </div>
                                <div class="form-group pull-right">
                                    <button class="btn btn-primary" onclick="addJobInputFolder()"> Add jobs <i class="fa fa-plus"></i></button>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="">Type of folder <span class="text-danger">*</span></label>
                                    <!--
                                     -- 1: Photo editing 2: Day to dusk	3: Virtual Staging	4: Additional Retouching
                                     -->
                                    <select name="type" id="type_job_multi" class="form-control">
                                        <option value="1">Photo editing</option>
                                        <option value="2">Day to dusk</option>
                                        <option value="3">Virtual Staging</option>
                                        <option value="4">Additional Retouching</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="note">Instruction <span class="text-danger">*</span></label>
                                    <textarea name="note" class="form-control" id="note_create_multi" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button class="btn btn-primary pull-right" onclick="createMultiJobs()">Create multiple job <i class="fa fa-plus-square"></i> </button>
                                <img src="{{ asset('loading.gif') }}" id="loading-btn-crate--multi-job" class="img-loading pull-right hidden" alt="">
                            </div>
                        </div>
                    </div>
                    <!-- end step 3 -->

                    <!-- step 4 -->
                    <div class="col-md-3 hidden" id="step-4">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Step 4: confirm</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body height-box-body">
                                <h3 class="text-success text-center">CREATE JOB SUCCESS.</h3>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button class="btn btn-primary pull-right" onclick="done()">Done <i class="fa fa-check"></i> </button>
                            </div>
                        </div>
                    </div>
                    <!-- end step 4 -->
                </div>
            </div>
        </div>
    </section>
    <style>
        .height-box-body {
            height: 250px;
        }
        .img-loading {
            height: 32px;
            width: 32px;
        }
        #box-select-customer{
            height: 300px;
            overflow: scroll;
        }
        #content-select-customer {
            height: auto;
        }
        .font-size-13 {
            font-size: 13px;
        }
        .btn_remove_job_a {
            margin-bottom: 15px;
        }
    </style>
@endsection

@section('custom-js')
    <script>
        $('#users').on('change', function () {
            $('#step-2').addClass('hidden');
            $('#step-3').addClass('hidden');
            $('#step-4').addClass('hidden');
        });

        /**
         * Step 2
         *
         * Call to API get main folder end change style
         */
        function nextStep2() {
            let user = "";
            let selected = $("input[type='radio'][name='users']:checked");
            if (selected.length > 0) {
                user = selected.val();
            }
            if (user === null) {
                alert('Please select 1 user');
            }
            $.ajax({
                url: '{{ url('/admin/sub-admin/get-main-folder') }}',
                method: 'GET',
                data: {
                    user: user
                },
                success: function (response) {
                    if (response.code === 200) {
                        let data = response.data;
                        if (data === null) {
                            $('#id_main_folder').val('');
                            $('#main_folder_name').text('');
                            $('#txt-warning-step-2').text("Haven't created a folder for today, click the create folder button before going to the next step.");
                            $('#btn-create-folder').removeClass('hidden');
                        } else {
                            $('#id_main_folder').val(data.id);
                            $('#main_folder_name').text(data.nas_dir);
                            $('#txt-warning-step-2').text('Folder for today already available, choose next step.');
                            $('#btn-create-folder').addClass('hidden');
                        }
                        $('#step-2').removeClass('hidden');
                    } else {
                        alert('There is an error in the system, please try again later.');
                    }
                }
            });
        }

        /**
         * Step 3
         *
         * Call to API check folder end create new folder
         */
        function nextStep3() {
            // Check folder already
            let idMainFolder = $('#id_main_folder').val();
            // Folder not found
            if (idMainFolder === '' || idMainFolder === null) {
                alert('Create a root directory for today.');
            }
            // Next step
            if (idMainFolder !== null && idMainFolder !== '') {
                $('#step-3').removeClass('hidden');
            }
        }

        /**
         * Step 3
         *
         * Call to API check folder end reate multiple jobs
         */
        function nextStep3MultipleJobs() {
            // Check folder already
            let idMainFolder = $('#id_main_folder').val();
            // Folder not found
            if (idMainFolder === '' || idMainFolder === null) {
                alert('Create a root directory for today.');
            }
            // Next step
            if (idMainFolder !== null && idMainFolder !== '') {
                $('#step-3-multi-jobs').removeClass('hidden');
            }
        }

        /**
         *  Step 4, create job end show box confirm
         */
        function nextStep4() {
            let user = "";
            let selected = $("input[type='radio'][name='users']:checked");
            if (selected.length > 0) {
                user = selected.val();
            }
            if (user === null) {
                alert('Please select 1 user');
            }
            let idMainFolder = $('#id_main_folder').val();
            let director = $('#name_job').val();
            let typeJob = $('#type_job').val();
            let noteJob = $('#note_create').val();
            $('#loading-btn-crate-job').removeClass('hidden');
            if (director === null || director === '') {
                alert('Please enter job name.');
            } else {
                $.ajax({
                   url: '{{ url('/admin/sub-admin/create-job') }}',
                   method: 'GET',
                   data: {
                       user: user,
                       idMainFolder: idMainFolder,
                       director: director,
                       typeJob: typeJob,
                       noteJob: noteJob,
                   },
                    success: function (response) {
                       if (response.code === 200) {
                           // show box step
                            $('#step-4').removeClass('hidden');
                           $('#loading-btn-crate-job').addClass('hidden');
                       } else {
                           alert('System error.');
                       }
                    }
                });
            }
        }

        /**
         * Function create main folder and change status element html
         */
        function createMainFolder() {
            let user = "";
            let selected = $("input[type='radio'][name='users']:checked");
            if (selected.length > 0) {
                user = selected.val();
            }
            if (user === null) {
                alert('Please select 1 user');
            }
            $('#loading-btn-crate-folder').removeClass('hidden');
            $.ajax({
                url: '{{ url('/admin/sub-admin/create-main-folders') }}',
                method: 'GET',
                data: {
                    user: user
                },
                success: function (response) {
                    console.log(response);
                    let data = response.data;
                    if (response.data === null) {
                        alert('System error.');
                    } else {
                        $('#id_main_folder').val(data.id);
                        $('#main_folder_name').text(data.nas_dir);
                        $('#txt-warning-step-2').text('Create folder success, choose next step.');
                        $('#btn-create-folder').addClass('hidden');
                        $('#loading-btn-crate-folder').addClass('hidden');
                    }
                }
            });
        }

        function createMultiJobs() {
            let user = "";
            let selected = $("input[type='radio'][name='users']:checked");
            if (selected.length > 0) {
                user = selected.val();
            }
            if (user === null) {
                alert('Please select 1 user');
            }
            let idMainFolder = $('#id_main_folder').val();
            let typeJob = $('#type_job_multi').val();
            let noteJob = $('#note_create_multi').val();
            $('#loading-btn-crate--multi-job').removeClass('hidden');
            let numberJobToBeCreated = Number($('#number-job-to-be-created').text());
            // Validate name of jobs
            let checkDirName = 0;
            for (let i = 1; i <= numberJobToBeCreated; i++) {
                if($('#input_name_job_' + i).val() === "" || $('#input_name_job_' + i).val() === null) {
                    checkDirName+=1;
                }
            }
            if (checkDirName > 0) {
                // alert and break
                alert('Please enter the job name!');
            }
            if (checkDirName === 0) {
                // Passed and continue
                let arrNameJobs = [];
                for (let i = 1; i <= numberJobToBeCreated; i++) {
                    arrNameJobs[i] = $('#input_name_job_' + i).val();
                }
                console.log(JSON.stringify(arrNameJobs));
                // Call to API
                $('#loading-btn-crate--multi-job').removeClass('hidden');
                $.ajax({
                    url: '{{ url('/admin/sub-admin/create-multi-job') }}',
                    method: 'GET',
                    data: {
                        user: user,
                        idMainFolder: idMainFolder,
                        director: arrNameJobs,
                        typeJob: typeJob,
                        noteJob: noteJob,
                        numberJobToBeCreated: numberJobToBeCreated
                    },
                    success: function (response) {
                        if (response.code === 200) {
                            // show box step
                            $('#step-4').removeClass('hidden');
                            $('#loading-btn-crate--multi-job').addClass('hidden');
                        } else {
                            alert('System error.');
                        }
                    }
                });
            }
        }

        /**
         * Function add new folder
         */
        function addJobInputFolder() {
            // Get number job to be created
            let numberJobToBeCreated = Number($('#number-job-to-be-created').text());
            // Add number count job
            numberJobToBeCreated = numberJobToBeCreated + 1;
            $('#number-job-to-be-created').text(numberJobToBeCreated);
            // Add input
            $('#name_job_multiple').append('' +
                '<input type="text" name="name_job_'+ numberJobToBeCreated +'" id="input_name_job_'+ numberJobToBeCreated +'" value="" class="form-control">' +
                '<button onclick="removeJobsCreated('+numberJobToBeCreated+')" id="btn_remove_job_'+numberJobToBeCreated+'" class="btn btn-primary btn_remove_job_a">Remove <i class="fa fa-trash"></i></button>'
            );
        }

        /**
         * Function remove job created
         */
        function removeJobsCreated(numberJobToBeCreated) {
            // Remove input jobs name
            $('#input_name_job_'+ numberJobToBeCreated).remove();
            $('#btn_remove_job_'+ numberJobToBeCreated).remove();
            // Sub number jobs folder
            numberJobToBeCreated = numberJobToBeCreated - 1;
            $('#number-job-to-be-created').text(numberJobToBeCreated);
        }

        /**
         * reload the page apter create success jobs
         */
        function done() {
            window.location.reload();
        }
    </script>
@endsection
