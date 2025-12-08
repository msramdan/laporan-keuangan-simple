<td>
    <a href="{{ route('nota-histories.regenerate', $model->id) }}" class="btn btn-outline-success btn-sm" title="Cetak Ulang">
        <i class="fa fa-print"></i>
    </a>
    <form action="{{ route('nota-histories.destroy', $model->id) }}" method="post" class="d-inline"
        onsubmit="return confirm('Yakin ingin menghapus riwayat ini?')">
        @csrf
        @method('delete')
        <button class="btn btn-outline-danger btn-sm" title="Hapus">
            <i class="fa fa-trash-alt"></i>
        </button>
    </form>
</td>
