@extends('super_admin.backend_layout.app')

@section('title', 'Active User')

@section('user-active', 'active')

@section('content')

<div class="d-flex mb-2 justify-content-between">
    <div>
        
     <button class='btn btn-info'>{{$users->total()}}</button>

    </div>
   
</div>

<div class="card card-dark card-outline">

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped" style="height:200px;">
                        <thead>
                            <tr>
                                <th>#id</th>
                                <th>User Name</th>
                                <th>Phone Number</th>
                                <th>User Code</th>
                                <th>Balance</th>
                                <th>status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->phone }}</td>
                                <td>{{ $row->user_code }}</td>
                                <td>{{ $row->balance }}</td>
                                 <td><span class="btn btn-success">Online</span></td>
                                     
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="d-flex justify-content-end">
                    {!! $users->appends(Request::all())->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')

@endsection
