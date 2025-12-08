<?php

namespace App\Http\Controllers;

use App\Http\Requests\Mesin\StoreMesinRequest;
use App\Http\Requests\Mesin\UpdateMesinRequest;
use App\Models\Mesin;
use Yajra\DataTables\Facades\DataTables;

class MesinController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $mesins = Mesin::query();

            return DataTables::of($mesins)
                ->addIndexColumn()
                ->editColumn('created_at', fn($row) => format_date($row->created_at))
                ->addColumn('action', 'mesins.include.action')
                ->toJson();
        }

        return view('mesins.index');
    }

    public function create()
    {
        return view('mesins.create');
    }

    public function store(StoreMesinRequest $request)
    {
        Mesin::create($request->validated());

        return redirect()->route('mesins.index')
            ->with('success', 'Mesin berhasil ditambahkan.');
    }

    public function show(Mesin $mesin)
    {
        return view('mesins.show', compact('mesin'));
    }

    public function edit(Mesin $mesin)
    {
        return view('mesins.edit', compact('mesin'));
    }

    public function update(UpdateMesinRequest $request, Mesin $mesin)
    {
        $mesin->update($request->validated());

        return redirect()->route('mesins.index')
            ->with('success', 'Mesin berhasil diperbarui.');
    }

    public function destroy(Mesin $mesin)
    {
        $mesin->delete();

        return redirect()->route('mesins.index')
            ->with('success', 'Mesin berhasil dihapus.');
    }
}
