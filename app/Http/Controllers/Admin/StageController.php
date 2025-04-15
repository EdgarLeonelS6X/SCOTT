<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StageController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stages = Stage::all();

        return view("admin.stages.index", compact("stages"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Stage::class);

        return view("admin.stages.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "status" => "required|string",
        ], [], [
            "name" => __('stage name'),
            "stage" => __('stage stage'),
        ]);

        $stage = Stage::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('New stage created successfully.')
        ]);

        return redirect()->route('admin.stages.show', $stage);
    }

    /**
     * Display the specified resource.
     */
    public function show(Stage $stage)
    {
        return view('admin.stages.show', compact('stage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stage $stage)
    {
        $this->authorize('edit', $stage);

        return view('admin.stages.edit', compact('stage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stage $stage)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|string',
        ], [], [
            'name' => __('stage name'),
            'status' => __('stage status'),
        ]);

        $stage->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('Stage updated successfully.')
        ]);

        return redirect()->route('admin.stages.show', $stage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stage $stage)
    {
        $this->authorize('delete', $stage);

        $stage->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('Stage deleted successfully.')
        ]);

        return redirect()->route('admin.stages.index');
    }
}
