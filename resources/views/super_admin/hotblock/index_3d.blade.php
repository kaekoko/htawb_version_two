@extends('super_admin.backend_layout.app')

@section('title', '3D Hot & Block Numbers')

@section('hotblock-3d-active', 'active')

@section('content')
<div class="card card-dark card-outline">
    <div class="card-body">
        <ul class="nav nav-tabs" id="account" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Hot Numbers</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Block Numbers</a>
            </li>
        </ul>
        <div class="tab-content" id="accountContent">
            <div class="tab-pane active" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <br>
                <button class="btn btn-dark btn-sm hot_number_btn" title="Add Hot Number">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add Hot Number
                </button>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Bet Number</th>
                                        <th>Bet Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hots as $item)
                                        <tr>
                                            <td>{{ $item->hot_number }}</td>
                                            <td>{{ $item->hot_amount }}</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default-{{ $item->id }}"><i class="fa fa-solid fa-pen"></i></button>
                                                <form id="postBtn{{ $item->id }}" method="post" action="{{ url('super_admin/hotblock_3d/delete/'.$item->id) }}" style="display: inline;">
                                                    @csrf
                                                </form>
                                                <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        {{-- hot number edit model --}}
                                        <div class="modal fade" id="modal-default-{{ $item->id }}">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hot Number</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/hot_3d/edit/'.$item->id) }}">
                                                        @csrf
                                                        <div class="form-group">
                                                        <input type="text" name="hot_number" class="form-control" value="{{ $item->hot_number }}" placeholder="Enter Hot Number eg:111,222,333" required>
                                                        <br>
                                                        <input type="text" name="hot_amount" class="form-control" value="{{ $item->hot_amount }}" placeholder="Enter Hot Amount eg:10000" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-dark">Update</button>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="contact-tab">
                <br>
                <button class="btn btn-dark btn-sm block_number_btn" title="Add Block Number">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add Block Number
                </button>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Block Number</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($blocks as $item)
                                        <tr>
                                            <td>{{ $item->block_number }}</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default-{{ $item->id }}"><i class="fa fa-solid fa-pen"></i></button>
                                                <form id="postBtn{{ $item->id }}" method="post" action="{{ url('super_admin/hotblock_3d/delete/'.$item->id) }}" style="display: inline;">
                                                    @csrf
                                                </form>
                                                <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        {{-- hot number edit model --}}
                                        <div class="modal fade" id="modal-default-{{ $item->id }}">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Block Number</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/block_3d/edit/'.$item->id) }}">
                                                        @csrf
                                                        <div class="form-group">
                                                        <input type="text" name="block_number" class="form-control" value="{{ $item->block_number }}" placeholder="Enter Block Number eg:111,222,333" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-dark">Update</button>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- hot number model --}}
<div class="modal fade modal-hot-number">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Hot Number</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/hot_3d/create') }}">
                @csrf
                <div class="form-group">
                  <input type="text" name="hot_number" class="form-control" placeholder="Enter Hot Number eg:111,222,333" required>
                  <br>
                  <input type="text" name="hot_amount" class="form-control" placeholder="Enter Hot Amount eg:10000" required>
                </div>
                <button type="submit" class="btn btn-dark">Add</button>
            </form>
        </div>
        </div>
    </div>
</div>

{{-- block number model --}}
<div class="modal fade modal-block-number">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Block Number</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/block_3d/create') }}">
                @csrf
                <div class="form-group">
                  <input type="text" name="block_number" class="form-control" placeholder="Enter Hot Number eg:111,222,333" required>
                </div>
                <button type="submit" class="btn btn-dark">Add</button>
            </form>
        </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $('.hot_number_btn').click(function(){
        $('.modal-hot-number').modal('show');
    });

    $('.block_number_btn').click(function(){
        $('.modal-block-number').modal('show');
    });
</script>
@endsection
