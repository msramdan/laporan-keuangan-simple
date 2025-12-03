<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fund\StoreFundRequest;
use App\Http\Requests\Fund\UpdateFundRequest;
use App\Models\Fund;
use Illuminate\Http\Request;

class FundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $funds = Fund::query();

            return \Yajra\DataTables\Facades\DataTables::of($funds)
                ->addIndexColumn()
                ->addColumn('action', 'funds.include.action')
                ->editColumn('amount', function ($row) {
                    return format_rupiah($row->amount);
                })
                ->editColumn('date', fn($row) => format_date($row->date, 'Y-m-d'))
                ->editColumn('type', function ($row) {
                    return $row->type == 'IN' ? '<span class="badge bg-success">Income</span>' : '<span class="badge bg-danger">Expense</span>';
                })
                ->editColumn('created_at', fn($row) => format_date($row->created_at))
                ->editColumn('updated_at', fn($row) => format_date($row->updated_at))
                ->rawColumns(['action', 'type'])
                ->toJson();
        }

        return view('funds.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('funds.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFundRequest $request)
    {
        Fund::create($request->validated());

        return redirect()->route('funds.index')
            ->with('success', 'Fund transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fund $fund)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fund $fund)
    {
        return view('funds.edit', compact('fund'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFundRequest $request, Fund $fund)
    {
        $fund->update($request->validated());

        return redirect()->route('funds.index')
            ->with('success', 'Fund transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fund $fund)
    {
        $fund->delete();

        return redirect()->route('funds.index')
            ->with('success', 'Fund transaction deleted successfully.');
    }
}
