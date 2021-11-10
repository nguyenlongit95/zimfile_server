@extends('admin.master')
@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ url('/admin/export') }}" class="btn btn-primary">Export jobs data</a>
        </div>
    </div>
</section>
@endsection
