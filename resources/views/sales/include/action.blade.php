<td>
    <a href="{{ route('sales.show', $model->id) }}" class="btn btn-outline-success btn-sm">
        <i class="fa fa-eye"></i>
    </a>

    <a href="{{ route('sales.print', $model->id) }}" class="btn btn-outline-secondary btn-sm" target="_blank" title="Cetak Nota">
        <i class="fa fa-print"></i>
    </a>

    <form action="{{ route('sales.destroy', $model->id) }}" method="post" class="d-inline"
        onsubmit="return confirm('Are you sure to delete this record?')">
        @csrf
        @method('delete')

        <button class="btn btn-outline-danger btn-sm">
            <i class="ace-icon fa fa-trash-alt"></i>
        </button>
    </form>
</td>
