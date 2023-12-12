@extends('super_admin.backend_layout.app')

@section('title', "Game's Categories")

@section('game-categories', 'active')

@section('content')
<a href="{{ route('game_categories.create') }}" title="Back"><button class="btn btn-info btn-sm">Create<i class="fa fa-arrow-right ml-1" aria-hidden="true"></i></button></a>
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
                                <th>ACTIVE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($g_cat as $cat)
                            <tr>
                                <td>{{ $cat->id }}</td>
                                <td>{{ $cat->name }}</td>
                                <td>@if ($cat->image)
                                    <img style="width: 50px;object-fit:cover;" src="{{ asset($cat->image) }}" />
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ $cat->code }}</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" data-id="{{$cat->id}}" {{ $cat->active ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="row"><a href="{{route('game_categories.edit',$cat->id)}}" class="btn btn-sm btn-info">EDIT</a>
                                    <form action="{{route('game_categories.destroy',$cat->id)}}" method="post">
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
                    type: 'category',
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
    })
</script>

@endsection