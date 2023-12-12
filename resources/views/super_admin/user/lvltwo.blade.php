@extends('super_admin.backend_layout.app')

@section('title', 'User')

@section('user-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/user/create') }}" class="btn btn-dark btn-sm" title="Add New Account">
        <i class="fa fa-plus" aria-hidden="true"></i> Add User
    </a>
    <br />
    <br />

    <ul class="nav nav-tabs" id="account" role="tablist">
        <li class="nav-item" role="presentation">
            <a href="{{ url('/super_admin/user') }}" class="btn btn-dark btn-sm"
                aria-controls="contact" aria-selected="false">Users</a>
        </li>
        
    </ul>
    <br><br>
            <div class="card card-dark card-outline">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data_table_super_admin" class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User Name</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lvl_2 as $n => $item)
                                            <tr>
                                                <td>{{ $n + 1 }}</td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>
                                                    <div class="filter-container">
                                                        <div class="filtr-item" data-category="1">
                                                            <a href="{{ asset($item->image) }}" data-toggle="lightbox"
                                                                data-title="Image of - {{ $item->user->name }}">
                                                                <img src="{{ asset($item->image) }}" width="50"
                                                                    class="mt-1">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($item->status == 'pending')
                                                        <small class="badge badge-warning">pending</small>
                                                    @elseif($item->status == 'approve')
                                                        <small class="badge badge-success">approve</small>
                                                    @else
                                                        <small class="badge badge-danger">reject</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->status == 'pending')
                                                        <form id="postBtn2{{ $item->id }}" method="post"
                                                            action="{{ url('super_admin/user/user_lvl_2/approve/' . $item->id) }}"
                                                            style="display: inline;">
                                                            @csrf
                                                        </form>
                                                        <button class="btn btn-success btn-sm confirmBtn2"
                                                            data-id={{ $item->id }}><i
                                                                class="fas fa-check-circle"></i></button>

                                                        <form id="postBtn1{{ $item->id }}" method="post"
                                                            action="{{ url('super_admin/user/user_lvl_2/reject/' . $item->id) }}"
                                                            style="display: inline;">
                                                            @csrf
                                                        </form>
                                                        <button class="btn btn-danger btn-sm deleteBtn1"
                                                            data-id={{ $item->id }}><i
                                                                class="fas fa-times-circle"></i></button>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


@endsection
@section('scripts')
    <script>
        //user
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: '{{ url('super_admin/user/add_block') }}',
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
        });

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
