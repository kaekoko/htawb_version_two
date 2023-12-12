@extends('super_admin.backend_layout.app')

@section('title', 'Cash in Promotion')

@section('promo-cash-in-active', 'active')

@section('content')
    <button type="submit" class="btn btn-dark add">Add Cash In Promo</button>
    <br><br>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th><th>Title</th><th>Percent</th><th>Promo Code</th><th>Turn Over</th><th>User Level</th><th>Image</th><th>Game Text</th><th>Rule</th><th>status</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($promo_cash_ins as $n=>$item)
                                    <tr>
                                        <td>{{ $n+1 }}</td>
                                        <td>{{ $item->title ?? '-' }}</td>
                                        <td>{{ $item->percent ?? '-' }}</td>
                                        <td>{{ $item->promo_code ?? '-' }}</td>
                                        <td>{{ $item->turnover ?? '-' }}</td>
                                        <td>
                                            @if($item->lvl == 1)
                                                Level 1
                                            @else
                                                Level 2
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->image)
                                                <img src="{{ asset($item->image) }}" width="50" class="mt-1">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($item->game_text, 10) ?? '-' }}</td>
                                        <td>{{ Str::limit($item->rule, 10) ?? '-' }}</td>
                                        <td>
                                            <input data-id="{{ $item->id }}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="On" data-off="Off" data-size="small" {{ $item->status == 0 ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-default-{{ $item->id }}"><i class="fa fa-solid fa-pen"></i></button>
                                            <form id="postBtn{{ $item->id }}" method="post" action="{{ url('super_admin/delete_cash_in_promo/'.$item->id) }}" style="display: inline;">
                                                @csrf
                                            </form>
                                            <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    {{-- promo update model --}}
                                    <div class="modal fade" id="modal-default-{{ $item->id }}">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Cash In Promotion</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" enctype="multipart/form-data" action="{{ url('super_admin/update_cash_in_promo/'.$item->id) }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="name">Title<span class="text-danger">*</span></label>
                                                                    <input type="text" name="title" class="form-control form-control-sm" value="{{ $item->title }}" placeholder="Enter Title" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="name">Percent<span class="text-danger">*</span></label>
                                                                    <input type="text" name="percent" class="form-control form-control-sm" value="{{ $item->percent }}" placeholder="eg: 100" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="name">Turnover<span class="text-danger">*</span></label>
                                                                    <input type="text" name="turnover" class="form-control form-control-sm" value="{{ $item->turnover }}" placeholder="eg: 100" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="name">Level<span class="text-danger">*</span></label>
                                                                    <select name="level" class="form-control select2">
                                                                        <option value="1" @if($item->lvl == 1) selected @endif>Level 1</option>
                                                                        <option value="2" @if($item->lvl == 2) selected @endif>Level 2</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="file">Image</label>
                                                                    <input type="file" class="form-control form-control-sm" name="image" onchange="loadFile(event)">
                                                                    @if ($item->image)
                                                                        <img src="{{ asset($item->image) }}" width="100" class="mt-1">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="name">Game</label>
                                                                    <input type="text" name="game_text" class="form-control form-control-sm" value="{{ $item->game_text }}" placeholder="eg: Slot,Fishing">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="name">Rule</label>
                                                                    <textarea class="form-control" name="rule" rows="3" placeholder="Enter ..." style="height: 102px;">{{ $item->rule }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <button type="submit" class="btn btn-dark">Update</button>
                                                            </div>
                                                        </div>
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

    {{-- promo add model --}}
    <div class="modal fade modal-add">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Cash In Promotion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="Post" enctype="multipart/form-data" action="{{ url('super_admin/create_cash_in_promo') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Title<span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="Enter Title">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Percent<span class="text-danger">*</span></label>
                                <input type="text" name="percent" class="form-control form-control-sm" placeholder="eg: 100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Turnover<span class="text-danger">*</span></label>
                                <input type="text" name="turnover" class="form-control form-control-sm" placeholder="eg: 100">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Level<span class="text-danger">*</span></label>
                                <select name="level" class="form-control select2">
                                    <option value="1">Level 1</option>
                                    <option value="2">Level 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="file">Image</label>
                                <input type="file" class="form-control form-control-sm" name="image" onchange="loadFile(event)">
                                <img id="output" width="100" class="mt-2"/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Game</label>
                                <input type="text" name="game_text" class="form-control form-control-sm" placeholder="eg: Slot,Fishing">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Rule</label>
                                <textarea class="form-control" name="rule" rows="3" placeholder="Enter ..." style="height: 102px;"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-dark">Create</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\PromoCashInRequest') !!}
    <script>
        $('.add').click(function(){
            $('.modal-add').modal('show');
        });

        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 0 : 1;
            var id = $(this).data('id');
            $.ajax({
                type:'POST',
                url:'{{ url("super_admin/status_cash_in_promo") }}',
                data:{ id:id, status:status },
                success:function(data){
                    if( data.status == 0 ){
                        toastr.success('Status Open!')
                    }else{
                        toastr.error('Status Close!')
                    }
                }
            });
        });
    </script>
@endsection
