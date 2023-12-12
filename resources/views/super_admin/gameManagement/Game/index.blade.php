@extends('super_admin.backend_layout.app')

@section('title', "Games")

@section('games', 'active')

@section('content')
<a href="{{ route('games.create') }}" title="Back"><button class="btn btn-info btn-sm">Create<i class="fa fa-arrow-right ml-1" aria-hidden="true"></i></button></a>
<br />
<br />

<div class="card">
    <div class="row m-2">
        <div class="form-group col-md-3">
            <label>Filter by Category</label>
            <select class="form-control select2" id="category_filter" data-column="2">
                <option value="">All Categories</option>
                @foreach($categories as $id => $cat)
                <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>Filter by Provider</label>
            <select class="form-control select2" id="provider_filter" data-column="3">
                <option value="">All Providers</option>
                @foreach($providers as $id => $prov)
                <option value="{{ $prov->name }}">{{ $prov->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6 mx-auto row mt-2">
            <div class="col-4 col-md-2">
                <h6>Active</h6>
                <input type="checkbox" @if (!$activechecked) checked @endif name="activecheckbox">
            </div>
            <div class="col-4 col-md-2">
                <h6>Hot</h6>
                <input type="checkbox" @if (!$hotchecked) checked @endif name="hotcheckbox">
            </div>
            <div class="col-4 col-md-2">
                <h6>New</h6>
                <input type="checkbox" @if (!$newchecked) checked @endif name="newcheckbox">
            </div>
        </div>

    </div>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="game_table" class="table table-striped table-sm w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>CATEGORY</th>
                                    <th>PROVIDER</th>
                                    <th>IMAGE</th>
                                    <th>GCODE</th>
                                    <th>TYPE</th>
                                    <th>ACTIVE</th>
                                    <th>HOT</th>
                                    <th>NEW</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let table = $('#game_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('games.index')}}",
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                }, {
                    data: "category_name",
                    name: "category.name"
                }, {
                    data: "provider_name",
                    name: "provider.name"
                }, {
                    data: "image",
                    serachable: false,
                    sortable: false,
                    render: function(data) {
                        return `<a href="${data}" target="_blank"><img src="${data}" style="width:50px;height:40px;object-fit:contain"></a>`;
                    }
                }, {
                    data: "g_code",
                    name: "g_code"
                }, {
                    data: "html_type",
                    name: "html_type"
                }, {
                    data: 'active',
                    name: 'active',
                }, {
                    data: 'is_hot',
                    name: 'is_hot',
                }, {
                    data: 'is_new',
                    name: 'is_new',
                }, {
                    data: "action",
                    name: "action",
                    serachable: false,
                    sortable: false
                }
            ],
        })

        $('#category_filter').change(function() {
            table.columns($(this).data('column'))
                .search($(this).val())
                .draw();
        });

        $('#provider_filter').change(function() {
            table.columns($(this).data('column'))
                .search($(this).val())
                .draw();
        })


        $(document).on('change', 'input[name="activecheckbox"]', function() {
            if (this.checked) {
                sendData('active', 1)
            } else {
                sendData('active', 0)
            }
        });

        $(document).on('change', 'input[name="hotcheckbox"]', function() {
            if (this.checked) {
                sendData('hot', 1)
            } else {
                sendData('hot', 0)
            }
        });
        $(document).on('change', 'input[name="newcheckbox"]', function() {
            if (this.checked) {
                sendData('new', 1)
            } else {
                sendData('new', 0)
            }
        });

        function sendData(type, status) {
            console.log(type, status);
            $.post("{{route('game.allStatusChange')}}", {
                type,
                status,
                _token: "{{ csrf_token() }}"
            }, (({
                success,
            }) => {
                if (success) location.reload()
            }))
        }
    })
</script>
@endsection