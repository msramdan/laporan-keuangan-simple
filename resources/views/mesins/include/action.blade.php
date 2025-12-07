<td>
    <a href="{{ route('mesins.edit', $model->id) }}" class="btn btn-outline-primary btn-sm">
        <i class="fa fa-pencil-alt"></i>
    </a>
    <form action="{{ route('mesins.destroy', $model->id) }}" method="post" class="d-inline"
        onsubmit="return confirm('Yakin ingin menghapus mesin ini?')">
        @csrf
        @method('delete')
        <button class="btn btn-outline-danger btn-sm">
            <i class="fa fa-trash-alt"></i>
        </button>
    </form>
</td>
