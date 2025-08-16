<div class="p-4 bg-white rounded shadow">
    <h2 class="text-lg font-bold mb-4">Registro de prueba de perfiles</h2>

    @if(session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-2">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Canal</label>
            <select wire:model="channel_id" class="w-full border rounded p-2">
                <option value="">-- Selecciona --</option>
                @foreach($this->channels as $channel)
                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                @endforeach
            </select>
            @error('channel_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium">Alto (10 Mbps)</label>
                <select wire:model="high" class="w-full border rounded p-2">
                    <option value="">-- Selecciona --</option>
                    <option value="Correcto">Correcto</option>
                    <option value="Se pausa">Se pausa</option>
                    <option value="Falla">Falla</option>
                </select>
                @error('high') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Medio (2.5-3.5 Mbps)</label>
                <select wire:model="medium" class="w-full border rounded p-2">
                    <option value="">-- Selecciona --</option>
                    <option value="Correcto">Correcto</option>
                    <option value="Se pausa">Se pausa</option>
                    <option value="Falla">Falla</option>
                </select>
                @error('medium') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Bajo (1.5-2.5 Mbps)</label>
                <select wire:model="low" class="w-full border rounded p-2">
                    <option value="">-- Selecciona --</option>
                    <option value="Correcto">Correcto</option>
                    <option value="Se pausa">Se pausa</option>
                    <option value="Falla">Falla</option>
                </select>
                @error('low') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
            Guardar
        </button>
    </form>
</div>
