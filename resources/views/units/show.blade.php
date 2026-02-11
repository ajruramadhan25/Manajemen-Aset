<x-app-layout>
    <div class="card">
        <div class="card-body">
            <h5>{{ $unit->unique_identifier ?? ('Unit #' . $unit->id) }}</h5>
            <p><strong>Aset:</strong> {{ $unit->asset->name ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($unit->status) }}</p>
            <p><strong>Catatan:</strong> {{ $unit->notes ?? '-' }}</p>
            <a href="{{ route('units.edit', $unit) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('units.index') }}" class="btn btn-outline-primary">Kembali</a>
        </div>
    </div>
</x-app-layout>
