<a class="btn btn-xs btn-info" href="{{ route('games.edit', $row->id) }}">
    Edit
</a>

<form action="{{ route('games.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Are you sure want to delete ?');" style="display: inline-block;">
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" class="btn btn-xs btn-danger" value="Delete">
</form>