<?php

namespace App\Http\Controllers;

use App\Http\Requests\Factory\StoreFactoryRequest;
use App\Http\Requests\Factory\UpdateFactoryRequest;
use App\Models\Factory;
use Illuminate\Http\Request;

class FactoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $factories = Factory::query();

            return \Yajra\DataTables\Facades\DataTables::of($factories)
                ->addIndexColumn()
                ->editColumn('created_at', fn($row) => format_date($row->created_at))
                ->editColumn('updated_at', fn($row) => format_date($row->updated_at))
                ->addColumn('action', 'factories.include.action')
                ->toJson();
        }

        return view('factories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('factories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFactoryRequest $request)
    {
        Factory::create($request->validated());

        return redirect()->route('factories.index')
            ->with('success', 'Factory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Factory $factory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Factory $factory)
    {
        return view('factories.edit', compact('factory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFactoryRequest $request, Factory $factory)
    {
        $factory->update($request->validated());

        return redirect()->route('factories.index')
            ->with('success', 'Factory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factory $factory)
    {
        $factory->delete();

        return redirect()->route('factories.index')
            ->with('success', 'Factory deleted successfully.');
    }
}
