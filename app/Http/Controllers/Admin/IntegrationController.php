<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Url;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $urls = Url::all();

        return view('admin.integrations.index', compact('urls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.integrations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
            'url' => ['required', 'string', 'max:255'],
            'api_key' => ['nullable', 'string', 'max:255'],
        ]);

        $url = Url::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'url' => $validated['url'],
        ]);

        if (!empty($validated['api_key'])) {
            $url->key()->create([
                'key' => $validated['api_key'],
            ]);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('New integration created successfully.')
        ]);

        return redirect()->route('admin.integrations.show', $url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $url = Url::with('key')->findOrFail($id);
        return view('admin.integrations.show', compact('url'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
