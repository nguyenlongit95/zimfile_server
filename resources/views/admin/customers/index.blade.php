@extends('admin.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="row">
        <div class="box">
                <div class="box-header">
                    <h3 class="box-title">List all customers</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @include('admin.layouts.alert')
                    <form action="{{ url('/admin/customers/search') }}" method="post">
                    <input class="fomr-control" name="_token" value="{{ csrf_token() }}" type="hidden">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>
                                Name:
                                <input class="fomr-control" name="name" type="text">
                            </th>
                            <th>
                                Email
                                <input class="fomr-control" name="email" type="email">
                            </th>
                            <th>
                                Address
                                <input class="fomr-control" name="address" type="text">
                            </th>
                            <th>
                                Phone
                                <input class="fomr-control" name="phone" type="text">
                            </th>
                            <th class="text-center">
                                <input class="btn btn-success" name="search" type="submit" value="Search">
                                <input class="btn btn-success" name="create" type="submit" value="Add New">
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
                    <h3 class="box-title">List all customers</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="thead-style">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th class="text-center">Update</th>
                            <th class="text-center">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>
                                {{ $customer->name }}
                            </td>
                            <td>
                                {{ $customer->email }}
                            </td>
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td class="text-center"><a href="/admin/customers/edit/{{$customer->id}}" class="btn btn-warning padding510510">Update</a></td>
                            <td class="text-center"><a href="/admin/customers/delete/{{$customer->id}}" class="btn btn-danger padding510510">Delete</a></td>
                        </tr>
                        @endforeach
                        </tfoot>
                    </table>
                    {!! $customers->appends($_GET)->links() !!}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
