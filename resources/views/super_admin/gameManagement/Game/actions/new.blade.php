<div class="form-check bg-danger mb-2">
    <input onchange="New('{{ $row->id }}')" class="form-check-input m-0" data-gid="{{$row->id}}" type="checkbox" id="new{{ $row->id }}" {{ $row->is_new === 1 ? 'checked' : '' }}>
</div>

<script>
    var New = (gid) => {
        if ($(`#new${gid}`).is(':checked')) {
            sendData("new", 1, gid)
        } else {
            sendData("new", 0, gid)
        }
    }

    //global function for actions
    function sendData(type, status, id) {
        console.log(type, status, id);
        $.post("{{route('game.statusChange')}}", {
            type,
            status,
            id,
            _token: "{{ csrf_token() }}"
        }, (({
            success,
            status,
            name
        }) => {
            console.log(status);
            if (success) {
                toastOption()
                status == 1 ? toastr.success(`Added ${type} in ${name}`) :
                    toastr.error(`Removed ${type} from ${name}`)
            }
        }))
    }

    function toastOption() {
        toastr.options = {
            "closeButton": true,
            "newestOnTop": true,
            "progressBar": true,
            "timeOut": "3000",
            "showMethod": "show",
            "hideMethod": "hide"
        }
    }
</script>