<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiMesin\StoreTransaksiMesinRequest;
use App\Http\Requests\TransaksiMesin\UpdateTransaksiMesinRequest;
use App\Models\Client;
use App\Models\Mesin;
use App\Models\TransaksiMesin;
use Yajra\DataTables\Facades\DataTables;

class TransaksiMesinController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $transaksis = TransaksiMesin::with(['client', 'mesin']);

            return DataTables::of($transaksis)
                ->addIndexColumn()
                ->editColumn('tanggal_transaksi', fn($row) => $row->tanggal_transaksi->format('Y-m-d'))
                ->addColumn('client_name', fn($row) => $row->client->name ?? '-')
                ->addColumn('mesin_name', fn($row) => $row->mesin->name ?? '-')
                ->editColumn('banyak_tsg', fn($row) => format_angka($row->banyak_tsg))
                ->editColumn('banyak_tsg_tertolak', fn($row) => format_angka($row->banyak_tsg_tertolak))
                ->editColumn('harga_pabrik', fn($row) => number_format($row->harga_pabrik, 0, ',', '.'))
                ->editColumn('harga_jual', fn($row) => number_format($row->harga_jual, 0, ',', '.'))
                ->editColumn('status_lunas', fn($row) => $row->status_lunas 
                    ? '<span class="badge bg-success">Lunas</span>' 
                    : '<span class="badge bg-warning">Belum Lunas</span>')
                ->addColumn('action', 'transaksi-mesins.include.action')
                ->rawColumns(['status_lunas', 'action'])
                ->toJson();
        }

        return view('transaksi-mesins.index');
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $mesins = Mesin::orderBy('name')->get();
        
        return view('transaksi-mesins.create', compact('clients', 'mesins'));
    }

    public function store(StoreTransaksiMesinRequest $request)
    {
        $data = $request->validated();
        $data['total_harga_pabrik'] = $data['harga_pabrik'];
        $data['total_harga_jual'] = $data['harga_jual'];
        $data['status_lunas'] = $request->boolean('status_lunas');
        
        TransaksiMesin::create($data);

        return redirect()->route('transaksi-mesins.index')
            ->with('success', 'Transaksi mesin berhasil ditambahkan.');
    }

    public function show(TransaksiMesin $transaksiMesin)
    {
        $transaksiMesin->load(['client', 'mesin']);
        return view('transaksi-mesins.show', compact('transaksiMesin'));
    }

    public function edit(TransaksiMesin $transaksiMesin)
    {
        $clients = Client::orderBy('name')->get();
        $mesins = Mesin::orderBy('name')->get();
        
        return view('transaksi-mesins.edit', compact('transaksiMesin', 'clients', 'mesins'));
    }

    public function update(UpdateTransaksiMesinRequest $request, TransaksiMesin $transaksiMesin)
    {
        $data = $request->validated();
        $data['total_harga_pabrik'] = $data['harga_pabrik'];
        $data['total_harga_jual'] = $data['harga_jual'];
        $data['status_lunas'] = $request->boolean('status_lunas');
        
        $transaksiMesin->update($data);

        return redirect()->route('transaksi-mesins.index')
            ->with('success', 'Transaksi mesin berhasil diperbarui.');
    }

    public function destroy(TransaksiMesin $transaksiMesin)
    {
        $transaksiMesin->delete();

        return redirect()->route('transaksi-mesins.index')
            ->with('success', 'Transaksi mesin berhasil dihapus.');
    }

    /**
     * Get transaction detail as JSON for modal
     */
    public function detail(TransaksiMesin $transaksiMesin)
    {
        $transaksiMesin->load(['client', 'mesin']);
        
        return response()->json([
            'id' => $transaksiMesin->id,
            'tanggal_transaksi' => $transaksiMesin->tanggal_transaksi->format('Y-m-d'),
            'client' => $transaksiMesin->client,
            'mesin' => $transaksiMesin->mesin,
            'nama_produk' => $transaksiMesin->nama_produk,
            'banyak_tsg' => $transaksiMesin->banyak_tsg,
            'banyak_tsg_tertolak' => $transaksiMesin->banyak_tsg_tertolak,
            'harga_pabrik' => $transaksiMesin->harga_pabrik,
            'harga_jual' => $transaksiMesin->harga_jual,
            'status_lunas' => $transaksiMesin->status_lunas,
        ]);
    }

    /**
     * Process payment/mark as paid for transaction
     */
    public function payment(TransaksiMesin $transaksiMesin)
    {
        $statusLunas = request()->boolean('status_lunas');
        
        $transaksiMesin->update(['status_lunas' => $statusLunas]);
        
        return response()->json([
            'success' => true,
            'message' => $statusLunas ? 'Transaksi berhasil ditandai lunas.' : 'Status pembayaran berhasil diperbarui.',
        ]);
    }
}

