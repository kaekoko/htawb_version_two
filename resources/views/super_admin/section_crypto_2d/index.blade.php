@extends('super_admin.backend_layout.app')

@section('title', 'Crypto 2D Time Section')

@section('section-crypto-2d-active', 'active')

@section('content')
    <div class="tab-content" id="accountContent">
        <div class="card card-dark">
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="data_table_custom_1" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($time_sections as $item)
                                        <tr>
                                            <td></td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $item->time_section)->format('h:i A') }}
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
@endsection
