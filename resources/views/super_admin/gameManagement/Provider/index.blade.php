@extends('super_admin.backend_layout.app')

@section('title', "Game's Provider")

@section('game-providers', 'active')

@section('content')
<a href="{{ route('game_providers.create') }}" title="Back"><button class="btn btn-info btn-sm">Create<i class="fa fa-arrow-right ml-1" aria-hidden="true"></i></button></a>
<br />
<br />
<div class="card card-dark card-outline">
    <div class="card-body">
        <div class="row mt-3">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="data_table_super_admin" class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>IMAGE</th>
                                <th>CODE</th>
                                <th>CATEGORY</th>
                                <th>ACTIVE</th>
                                <th>HOT</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($g_prov as $prov)
                            <tr>
                                <td>{{ $prov->id }}</td>
                                <td>{{ $prov->name }}</td>
                                <td>@if ($prov->image)
                                    <img style="width: 50px;object-fit:cover;" src="{{ asset($prov->image) }}" />
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ $prov->p_code }}</td>
                                <td>@foreach ($prov->categories as $cat )
                                    <span class="bg-info mr-1 px-1">{{ $cat->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" data-id="{{$prov->id}}" {{ $prov->active ? 'checked' : '' }}>
                                    </div>
                                </td>

                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input-hot" type="checkbox" data-id="{{$prov->id}}" {{ $prov->hot ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="row"><a href="{{route('game_providers.edit',$prov->id)}}" class="btn btn-sm btn-info">EDIT</a>
                                    <form action="{{route('game_providers.destroy',$prov->id)}}" method="post">
                                        @csrf @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger ml-1">REMOVE</button>
                                    </form>
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
    $(document).ready(function() {

        $('#data_table_super_admin').on('change', '.form-check-input', function() {
            const id = ($(this).data('id'));
            const status = ($(this).is(":checked"));

            $.post(
                "{{ route('statusChange') }}", {
                    id,
                    type: 'provider',
                    status,
                    token: "{{ csrf_token() }}"
                }, ({
                    success,
                    status,
                    name
                }) => {
                    console.log(status);
                    if (success) {
                        status == 1 ? toastr.success(`Added Active in ${name}`) :
                            toastr.warning(`Removed Active from ${name}`)
                    }
                }
            )
        })

        $('#data_table_super_admin').on('change', '.form-check-input-hot', function() {
            const id = ($(this).data('id'));
            const status = ($(this).is(":checked"));

            $.post(
                "{{ route('statusChange') }}", {
                    id,
                    type: 'provider',
                    hot: true,
                    status,
                    token: "{{ csrf_token() }}"
                }, ({
                    success,
                    status,
                    name
                }) => {
                    if (success) {
                        status == 1 ? toastr.success(`Added Hot in ${name}`) :
                            toastr.warning(`Removed Hot from ${name}`)
                    }
                }
            )
        })
    })
</script>
@endsection
