<td>
    <a href="{{ route('units.edit', $model->id) }}" class="btn btn-outline-success btn-sm">
        <i class="fa fa-pencil-alt"></i>
    </a>
    <form action="{{ route('units.destroy', $model->id) }}" method="post" class="d-inline"
        onsubmit="return confirm('Are you sure to delete this unit?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">
            <i class="fa fa-trash-alt"></i>
        </button>
    </form>
</td>
