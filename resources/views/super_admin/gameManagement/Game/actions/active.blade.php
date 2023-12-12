<div class="form-check bg-danger mb-2">
    <input onchange="Active('{{ $row->id }}')" class="form-check-input m-0" data-gid="{{$row->id}}" type="checkbox" id="active{{ $row->id }}" {{ $row->active === 1 ? 'checked' : '' }}>
</div>

<script>
    var Active = (gid) => {
        if ($(`#active${gid}`).is(':checked')) {
            sendData("active", 1, gid)
        } else {
            sendData("active", 0, gid)
        }
    }
</script>