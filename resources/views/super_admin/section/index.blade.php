@extends('super_admin.backend_layout.app')

@section('title', 'Time Section')

@section('section-active', 'active')

@section('content')
    <ul class="nav nav-tabs" id="account" role="tablist">
    <li class="nav-item" role="presentation">
            <a class="nav-link active" id="contact-1d-tab" data-toggle="tab" href="#contact-1d" role="tab" aria-controls="contact-1d"
                aria-selected="false">1D SECTION</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact"
                aria-selected="false">2D SECTION</a>
        </li>
    <li class="nav-item" role="presentation">
            <a class="nav-link " id="contact-c1d-tab" data-toggle="tab" href="#contact-c1d" role="tab" aria-controls="contact-c1d"
                aria-selected="false">Crypto 1D SECTION</a>
        </li>
    
        <li class="nav-item" role="presentation">
            <a class="nav-link " id="crypto-2d-tab" data-toggle="tab" href="#crypto-2d" role="tab"
                aria-controls="crypto-2d" aria-selected="false">CRYPTO 2D SECTION</a>
        </li>
        
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                aria-selected="false">3D SECTION</a>
        </li>
    </ul>
    <div class="tab-content" id="accountContent">
    {{--Crypto 1D  --}}
        <div class="tab-pane " id="contact-c1d" role="tabpanel" aria-labelledby="contact-c1d-tab">
            {{-- <br/>
            <a href="{{ url('/super_admin/section/create') }}" class="btn btn-dark btn-sm" title="Add Section">
                <i class="fa fa-plus" aria-hidden="true"></i> Add Section
            </a> --}}
            <br />
            <br />
            <div class="card card-dark">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                            <table id="data_table_custom_2" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Time Section</th>
                                            <th>Open Time</th>
                                            <th>Close Time</th>
                                            <th>Is Opened?</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sections_c1d as $item)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->time_section)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->open_time)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->close_time)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    {{-- Is Open or Not --}}
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input onchange="handleSwitchChangec1d({{$item->id}})" style="cursor: pointer;" onchange=""
                                                                {{ $item->is_open ? 'checked' : '' }} name="is_open_3d"
                                                                id="switchc1dSectionToggle{{$item->id}}" type="checkbox"
                                                                class="custom-control-input">
                                                            <label style="cursor: pointer;" class="custom-control-label"
                                                                for="switchc1dSectionToggle{{$item->id}}"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ url('super_admin/section_c1d/' . $item->id . '/edit') }}"
                                                        title="Edit"><button class="btn btn-primary btn-sm"><i
                                                                class="fa fa-solid fa-pen"></i></button></a>
                                                    <form id="postBtn{{ $item->id }}" method="post"
                                                        action="{{ route('section.destroy', $item->id) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    {{-- <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button> --}}
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
        </div>
    {{-- 1D  --}}
        <div class="tab-pane active" id="contact-1d" role="tabpanel" aria-labelledby="contact-1d-tab">
            {{-- <br/>
            <a href="{{ url('/super_admin/section/create') }}" class="btn btn-dark btn-sm" title="Add Section">
                <i class="fa fa-plus" aria-hidden="true"></i> Add Section
            </a> --}}
            <br />
            <br />
            <div class="card card-dark">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                            <table id="data_table_custom_2" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Time Section</th>
                                            <th>Open Time</th>
                                            <th>Close Time</th>
                                            <th>Is Opened?</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sections_1d as $item)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->time_section)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->open_time)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->close_time)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    {{-- Is Open or Not --}}
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input onchange="handleSwitchChangeoned({{$item->id}})" style="cursor: pointer;" onchange=""
                                                                {{ $item->is_open ? 'checked' : '' }} name="is_open_3d"
                                                                id="switch1dSectionToggle{{$item->id}}" type="checkbox"
                                                                class="custom-control-input">
                                                            <label style="cursor: pointer;" class="custom-control-label"
                                                                for="switch1dSectionToggle{{$item->id}}"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ url('super_admin/section_1d/' . $item->id . '/edit') }}"
                                                        title="Edit"><button class="btn btn-primary btn-sm"><i
                                                                class="fa fa-solid fa-pen"></i></button></a>
                                                    <form id="postBtn{{ $item->id }}" method="post"
                                                        action="{{ route('section.destroy', $item->id) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    {{-- <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button> --}}
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
        </div>

        
        {{-- Crypto 2D  --}}
        <div class="tab-pane " id="crypto-2d" role="tabpanel" aria-labelledby="crypto-2d-tab">
            {{-- <br/>
            <a href="{{ url('/super_admin/section/create') }}" class="btn btn-dark btn-sm" title="Add Section">
                <i class="fa fa-plus" aria-hidden="true"></i> Add Section
            </a> --}}
            <br />
            <br />
            <div class="card card-dark">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data_table_custom_1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Time Section</th>
                                            <th>Open Time</th>
                                            <th>Close Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($crypto_time_sections as $item)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->time_section)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->open_time)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->close_time)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    <a href="{{ url('super_admin/section_c2d/' . $item->id . '/edit') }}"
                                                        title="Edit"><button class="btn btn-primary btn-sm"><i
                                                                class="fa fa-solid fa-pen"></i></button></a>
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
        </div>
        {{-- 2D  --}}
        <div class="tab-pane" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            {{-- <br/>
            <a href="{{ url('/super_admin/section/create') }}" class="btn btn-dark btn-sm" title="Add Section">
                <i class="fa fa-plus" aria-hidden="true"></i> Add Section
            </a> --}}
            <br />
            <br />
            <div class="card card-dark">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data_table_custom_2" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Time Section</th>
                                            <th>Open Time</th>
                                            <th>Close Time</th>
                                            <th>ODD</th>
                                            <th>Is Opened?</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($time_sections as $item)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->time_section)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->open_time)->format('h:i A') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->close_time)->format('h:i A') }}
                                                </td>
                                                <td>{{ $item->odd }}</td>
                                                <td>
                                                    {{-- Is Open or Not --}}
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input onchange="handleSwitchChange({{$item->id}})" style="cursor: pointer;" onchange=""
                                                                {{ $item->is_open ? 'checked' : '' }} name="is_open_3d"
                                                                id="switch2dSectionToggle{{$item->id}}" type="checkbox"
                                                                class="custom-control-input">
                                                            <label style="cursor: pointer;" class="custom-control-label"
                                                                for="switch2dSectionToggle{{$item->id}}"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ url('super_admin/section/' . $item->id . '/edit') }}"
                                                        title="Edit"><button class="btn btn-primary btn-sm"><i
                                                                class="fa fa-solid fa-pen"></i></button></a>
                                                    <form id="postBtn{{ $item->id }}" method="post"
                                                        action="{{ route('section.destroy', $item->id) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    {{-- <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button> --}}
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
        </div>
        {{-- 3D  --}}
        <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="contact-tab">
            {{-- <br/>
            <a href="{{ url('/super_admin/section_3d/create') }}" class="btn btn-dark btn-sm" title="Add Section">
                <i class="fa fa-plus" aria-hidden="true"></i> Add Section
            </a> --}}
            <br />
            <br />
            <div class="card card-dark">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="data_table_auto_1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Close Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sections_3d as $item)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    {{ $item->date }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $item->time)->format('g:i A') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $item->close_time)->format('g:i A') }}
                                                </td>
                                                <td>
                                                    <a href="{{ url('super_admin/section_3d/' . $item->id . '/edit') }}"
                                                        title="Edit"><button class="btn btn-primary btn-sm"><i
                                                                class="fa fa-solid fa-pen"></i></button></a>
                                                    <form id="postBtn{{ $item->id }}" method="post"
                                                        action="{{ url('super_admin/section_3d/' . $item->id . '/destroy') }}"
                                                        style="display: inline;">
                                                        @csrf
                                                    </form>
                                                    {{-- <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $item->id }}><i class="fa fa-trash"></i></button> --}}
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
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const handleOpenOrNot = (is_open, id) => {
            $.ajax({
                method: 'POST',
                url: location.protocol + "//" + location.host + '/super_admin/open_or_not_section',
                data: {
                    is_open,
                    id
                },
                success: function(data) {
                    toastr.success(data?.message)
                },
                error: function(err) {
                    toastr.error(err?.responseJSON?.message)
                }
            });
        }

       const handleSwitchChange = (id) => {
            let isChecked = $(`#switch2dSectionToggle${id}`).is(":checked");
                if (isChecked)
                    handleOpenOrNot(1, id);
                else
                    handleOpenOrNot(0, id);
       }

       const handleOpenOrNotoned = (is_open, id) => {
            $.ajax({
                method: 'POST',
                url: location.protocol + "//" + location.host + '/super_admin/open_or_not_section_1d',
                data: {
                    is_open,
                    id
                },
                success: function(data) {
                    toastr.success(data?.message)
                },
                error: function(err) {
                    toastr.error(err?.responseJSON?.message)
                }
            });
        }

       const handleSwitchChangeoned = (id) => {
            let isChecked = $(`#switch1dSectionToggle${id}`).is(":checked");
                if (isChecked)
                handleOpenOrNotoned(1, id);
                else
                handleOpenOrNotoned(0, id);
       }


       const handleOpenOrNotc1d = (is_open, id) => {
            $.ajax({
                method: 'POST',
                url: location.protocol + "//" + location.host + '/super_admin/open_or_not_section_c1d',
                data: {
                    is_open,
                    id
                },
                success: function(data) {
                    toastr.success(data?.message)
                },
                error: function(err) {
                    toastr.error(err?.responseJSON?.message)
                }
            });
        }

       const handleSwitchChangec1d = (id) => {
            let isChecked = $(`#switchc1dSectionToggle${id}`).is(":checked");
                if (isChecked)
                handleOpenOrNotc1d(1, id);
                else
                handleOpenOrNotc1d(0, id);
       }


    </script>

@endsection
