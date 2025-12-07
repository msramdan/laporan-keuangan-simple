<?php

namespace App\Http\Controllers;

use App\Http\Requests\Paket\StorePaketRequest;
use App\Http\Requests\Paket\UpdatePaketRequest;
use App\Models\Paket;
use Yajra\DataTables\Facades\DataTables;

class PaketController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $pakets = Paket::query();

            return DataTables::of($pakets)
                ->addIndexColumn()
                ->editColumn('created_at', fn($row) => format_date($row->created_at))
                ->addColumn('action', 'pakets.include.action')
                ->toJson();
        }

        return view('pakets.index');
    }

    public function create()
    {
        return view('pakets.create');
    }

    public function store(StorePaketRequest $request)
    {
        Paket::create($request->validated());

        return redirect()->route('pakets.index')
            ->with('success', 'Paket berhasil ditambahkan.');
    }

    public function show(Paket $paket)
    {
        return view('pakets.show', compact('paket'));
    }

    public function edit(Paket $paket)
    {
        return view('pakets.edit', compact('paket'));
    }

    public function update(UpdatePaketRequest $request, Paket $paket)
    {
        $paket->update($request->validated());

        return redirect()->route('pakets.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Paket $paket)
    {
        $paket->delete();

        return redirect()->route('pakets.index')
            ->with('success', 'Paket berhasil dihapus.');
    }
}
