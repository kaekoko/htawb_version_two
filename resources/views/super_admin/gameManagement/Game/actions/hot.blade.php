<div class="form-check bg-danger mb-2">
    <input onchange="Hot('{{ $row->id }}')" class="form-check-input m-0" data-gid="{{$row->id}}" type="checkbox" id="hot{{ $row->id }}" {{ $row->is_hot === 1 ? 'checked' : '' }}>
</div>

<script>
    var Hot = (gid) => {
        if ($(`#hot${gid}`).is(':checked')) {
            sendData("hot", 1, gid)
        } else {
            sendData("hot", 0, gid)
        }
    }
</script>