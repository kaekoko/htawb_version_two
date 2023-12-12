@extends('super_admin.backend_layout.app')

@section('title', 'ICASINO User')

@section('game-user-active', 'active')

@section('content')

<div class="d-flex mb-2 justify-content-between">
    <div>
        <a href="{{ url('/game/user') }}" class="btn btn-info reload" title="refresh"><i class="nav-icon fas fa-retweet"></i></a>

        <a href="{{ url('/game/user/create') }}" class="btn btn-dark" title="Add New Account">
            <i class="fa fa-plus mr-1" aria-hidden="true"></i> Add User
        </a>


    </div>
    <form action="{{ url('/game/user') }}" method="GET" class="d-flex justify-content-end">
        <input type="search" name="search" required placeholder={{ Request::get('search') ?? 'enter-keyword' }} class="form-control  mr-3">
        <button type="submit" class="btn btn-outline-dark">Search</button>
    </form>
</div>

<div class="card card-dark card-outline">

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped" style="height:200px;">
                        <thead>
                            <tr>
                                <th>#No.</th>
                                <th>User Name</th>
                                <th>Phone Number</th>
                                <th>User Code</th>
                                <th>Balance</th>
                                <th>Date</th>
                                <th>Block</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $row)
                            <tr>
                                <td>{{ $row->id}}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->phone }}</td>
                                <td>{{ $row->user_code }}</td>
                                <td>{{ $row->balance }}</td>
                                <td>{{ $row->created_at }}</td>
                                <td>@include('super_admin.user.block')</td>
                                <td>@include('super_admin.casino.action')</td>
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
<script>
    //user
    const toggleBlock = (id) => {
        var status = $(`.toggle-class_${id}`).prop('checked') == true ? 1 : 0;
        $.ajax({
            type: 'POST',
            url: "{{ url('super_admin/user/add_block') }}",
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.message == 1) {
                    toastr.error(data.name + ' has been blocked')
                }
                if (data.message == 0) {
                    toastr.success(data.name + ' has been unblocked')
                }
            }
        });
    }

    //approve
    $('.confirmBtn2').click(function() {
        var data_id = $(this).attr("data-id");
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#postBtn2" + data_id).submit();
            }
        })
    });

    //reject
    $('.deleteBtn1').click(function() {
        var data_id = $(this).attr("data-id");
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#postBtn1" + data_id).submit();
            }
        })
    });

    //image click show photo
    $(function() {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });

        $('.btn[data-filter]').on('click', function() {
            $('.btn[data-filter]').removeClass('active');
            $(this).addClass('active');
        });
    })
</script>
@endsection
