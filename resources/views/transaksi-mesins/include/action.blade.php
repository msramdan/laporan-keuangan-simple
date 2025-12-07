<td>
    <button type="button" class="btn btn-outline-info btn-sm btn-view-detail" data-id="{{ $model->id }}" title="Lihat Detail & Bayar">
        <i class="fa fa-eye"></i>
    </button>
    <a href="{{ route('transaksi-mesins.edit', $model->id) }}" class="btn btn-outline-primary btn-sm" title="Edit">
        <i class="fa fa-pencil-alt"></i>
    </a>
    <form action="{{ route('transaksi-mesins.destroy', $model->id) }}" method="post" class="d-inline"
        onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
        @csrf
        @method('delete')
        <button class="btn btn-outline-danger btn-sm" title="Hapus">
            <i class="fa fa-trash-alt"></i>
        </button>
    </form>
</td>
