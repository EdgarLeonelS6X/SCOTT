<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrafanaPanel;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GrafanaController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', GrafanaPanel::class);

        $panels = GrafanaPanel::all();

        return view('admin.grafana.index', compact('panels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', GrafanaPanel::class);

        return view('admin.grafana.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'api_url' => ['nullable', 'string', 'max:255'],
            'endpoint' => ['nullable', 'string', 'max:255'],
            'api_key' => ['nullable', 'string', 'max:255'],
        ]);

        $panel = GrafanaPanel::create([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'api_url' => $validated['api_url'] ?? null,
            'endpoint' => $validated['endpoint'] ?? null,
            'api_key' => $validated['api_key'] ?? null,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('New Grafana panel created successfully.')
        ]);

        return redirect()->route('admin.grafana.show', $panel);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $panel = GrafanaPanel::findOrFail($id);

        return view('admin.grafana.show', compact('panel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $panel = GrafanaPanel::findOrFail($id);

        $this->authorize('edit', $panel);

        return view('admin.grafana.edit', compact('panel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $panel = GrafanaPanel::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'api_url' => ['nullable', 'string', 'max:255'],
            'endpoint' => ['nullable', 'string', 'max:255'],
            'api_key' => ['nullable', 'string', 'max:255'],
        ]);

        $panel->update([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'api_url' => $validated['api_url'] ?? null,
            'endpoint' => $validated['endpoint'] ?? null,
            'api_key' => $validated['api_key'] ?? null,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('Grafana panel updated successfully.')
        ]);

        return redirect()->route('admin.grafana.show', $panel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $panel = GrafanaPanel::findOrFail($id);

        $this->authorize('delete', $panel);

        $panel->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('Grafana panel deleted successfully.')
        ]);

        return redirect()->route('admin.grafana.index');
    }
}
