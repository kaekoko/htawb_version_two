@extends('super_admin.backend_layout.app')

@section('title', 'Notification')

@section('noti-active', 'active')

@section('content')
    <a href="{{ url('/super_admin/noti/create') }}" class="btn btn-dark btn-sm" title="Add Noti">
        <i class="fa fa-plus" aria-hidden="true"></i> Add Noti
    </a>
    <br/>
    <br/>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th><th>Title</th><th>Noti Send</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notis as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->title ?? '-' }}</td>
                                        <td>
                                            <a href="{{ url('super_admin/update_noti/'.$item->id) }}" title="Send Noti"><button class="btn btn-success btn-sm"><i class="fa fa-solid fa-bell"></i></button></a>
                                            {{-- @if($item->noti == 0)
                                                <input data-id="{{ $item->id }}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="On" data-off="Off" data-size="small" {{ $item->noti === 0 ? '' : 'checked' }}>
                                                <form id="switchForm{{ $item->id }}" method="post" action="{{ url('super_admin/update_noti/'.$item->id) }}" style="display: inline;">
                                                    @csrf
                                                    <input type="hidden" class="onBtn{{ $item->id }}" value="1">
                                                    <input type="hidden" class="offBtn{{ $item->id }}" value="0">
                                                </form>
                                            @endif
                                            @if($item->noti == 1)
                                                <i class="nav-icon fas fa-bell"></i>
                                            @endif --}}
                                        </td>
                                        <td>
                                            <a href="{{ url('super_admin/noti/'.$item->id.'/edit') }}" title="Edit"><button class="btn btn-primary btn-sm"><i class="fa fa-solid fa-pen"></i></button></a>
                                            <form id="postBtn{{ $item->id }}" method="post" action="{{ route('noti.destroy', $item->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button>
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
        // $('.toggle-class').change(function() {
        //     var status = $(this).prop('checked') == true ? 1 : 0;
        //     var item_id = $(this).data('id');
        //     if(status == 0){
        //         $('.offBtn'+item_id).attr( "name", "status" );
        //     }else{
        //         $('.onBtn'+item_id).attr( "name", "status" );
        //     }
        //     $("#switchForm"+ item_id).submit();
        // });
    </script>
@endsection
