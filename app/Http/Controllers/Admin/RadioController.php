<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Radio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RadioController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();

        if (! ($user && $user->id === 1)) {
            $this->authorize('viewAny', Radio::class);
        }

        $radios = Radio::when($user && $user->id !== 1, function ($query) use ($user) {
            return $query->where('area', $user->area);
        })->get();

        return view('admin.radios.index', compact('radios'));
    }

    public function create()
    {
        $this->authorize('create', Radio::class);

        return view('admin.radios.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Radio $radio)
    {
        $this->authorize('view', $radio);

        return view('admin.radios.show', compact('radio'));
    }

    public function edit(Radio $radio)
    {
        if ($radio->id === 10 && (! Auth::user() || Auth::id() !== 1)) {
            abort(403);
        }

        $this->authorize('update', $radio);

        return view('admin.radios.edit', compact('radio'));
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
