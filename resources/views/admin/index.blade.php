@extends('admin.master')
@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
            <form action="{{ url('/admin/export') }}" method="GET">
                <div class="col-md-2">
                    <select class="form-control" name="month" id="month">
                        <option value="0">This month</option>
                        <option value="1">Last month</option>
                        <option value="2">2 months ago</option>
                        <option value="3">3 months ago</option>
                        <option value="4">4 months ago</option>
                        <option value="5">5 months ago</option>
                        <option value="6">6 months ago</option>
                    </select>
                </div>
                <input type="submit" name="" class="btn btn-primary" id="" value="Export jobs data">
            </form>
            </div>
        </div>
    </div>
</section>
@endsection
