<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex gap-3 align-items-center">
            <div class="col-md-6">
                <select wire:model.live="selectedAirlineId" class="form-select form-select-sm">
                    <option value="">All Airlines</option>
                    @foreach ($airlines as $airline)
                        <option value="{{ $airline->id }}">{{ $airline->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <input type="search" wire:model.live="search" class="form-control form-control-sm"
                    placeholder="Search aircraft types...">
            </div>
        </div>
        <button class="btn btn-primary btn-sm" wire:click="$toggle('showForm')" data-bs-toggle="modal"
            data-bs-target="#aircraftTypeFormModal">
            <i class="bi bi-plus-circle"></i> Add Aircraft Type
        </button>
    </div>

    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-3">
                <div class="list-group">
                    @forelse ($aircraftTypes as $type)
                        <div class="list-group-item {{ $selectedTypeId === $type->id ? 'active' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <button wire:click="selectType({{ $type->id }})"
                                    class="btn btn-link text-decoration-none p-0 {{ $selectedTypeId === $type->id ? 'text-white' : 'text-dark' }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <strong>{{ $type->code }}</strong>
                                        <span class="badge bg-secondary">{{ $type->category }}</span>
                                    </div>
                                    <small class="text-{{ $selectedTypeId === $type->id ? 'white' : 'muted' }}">
                                        {{ $type->manufacturer }} {{ $type->name }}
                                    </small>
                                    <div class="small mt-1">
                                        <div class="row text-{{ $selectedTypeId === $type->id ? 'white' : 'muted' }}">
                                            <div class="col">
                                                <i class="bi bi-person"></i> {{ $type->max_passengers }}pax
                                            </div>
                                            <div class="col">
                                                <i class="bi bi-arrow-up"></i> {{ number_format($type->max_takeoff_weight) }}kg
                                            </div>
                                            <div class="col">
                                                <i class="bi bi-fuel-pump"></i> {{ number_format($type->max_fuel_capacity) }}L
                                            </div>
                                        </div>
                                    </div>
                                </button>
                                <div class="d-flex gap-2">
                                    <button
                                        class="btn btn-sm btn-link {{ $selectedTypeId === $type->id ? 'text-white' : '' }}"
                                        wire:click="edit({{ $type->id }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#aircraftTypeFormModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button
                                        class="btn btn-sm btn-link {{ $selectedTypeId === $type->id ? 'text-white' : 'text-danger' }}"
                                        wire:click="delete({{ $type->id }})"
                                        wire:confirm="Are you sure you want to remove this aircraft type?">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            No aircraft types found
                        </div>
                    @endforelse
                </div>

                <div class="mt-3">
                    {{ $aircraftTypes->links() }}
                </div>
            </div>

            <div class="col-md-9">
                @if ($this->selectedType)
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item">
                                    <button class="nav-link {{ $activeTab === 'overview' ? 'active' : '' }}"
                                        wire:click="$set('activeTab', 'overview')">
                                        <i class="bi bi-info-circle"></i> Overview
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link {{ $activeTab === 'holds' ? 'active' : '' }}"
                                        wire:click="$set('activeTab', 'holds')">
                                        <i class="bi bi-box"></i> Holds
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link {{ $activeTab === 'zones' ? 'active' : '' }}"
                                        wire:click="$set('activeTab', 'zones')">
                                        <i class="bi bi-collection"></i> Zones
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link {{ $activeTab === 'aircraft' ? 'active' : '' }}"
                                        wire:click="$set('activeTab', 'aircraft')">
                                        <i class="bi bi-airplane"></i> Aircraft
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link {{ $activeTab === 'settings' ? 'active' : '' }}"
                                        wire:click="$set('activeTab', 'settings')">
                                        <i class="bi bi-gear"></i> Settings
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            @if ($activeTab === 'overview')
                                <livewire:aircraft-type.overview :key="'overview-' . $selectedTypeId" :aircraftType="$this->selectedType" />
                            @elseif ($activeTab === 'holds')
                                <livewire:aircraft-type.hold-manager :key="'holds-' . $selectedTypeId" :aircraftType="$this->selectedType" />
                            @elseif ($activeTab === 'zones')
                                <livewire:aircraft-type.zone-manager :key="'zones-' . $selectedTypeId" :aircraftType="$this->selectedType" />
                            @elseif ($activeTab === 'aircraft')
                                <livewire:aircraft-type.aircraft-manager :key="'aircraft-' . $selectedTypeId" :aircraftType="$this->selectedType" />
                            @elseif ($activeTab === 'settings')
                                <livewire:aircraft-type.settings :key="'settings-' . $selectedTypeId" :aircraftType="$this->selectedType" />
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center text-muted py-5">
                            <i class="bi bi-airplane display-1"></i>
                            <p class="mt-3">Select an aircraft type to view details</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="aircraftTypeFormModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editingAircraftType ? 'Edit' : 'Add' }} Aircraft Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Code</label>
                                <input type="text" class="form-control form-control-sm" wire:model="form.code">
                                @error('form.code')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control form-control-sm" wire:model="form.name">
                                @error('form.name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Manufacturer</label>
                                <input type="text" class="form-control form-control-sm" wire:model="form.manufacturer">
                                @error('form.manufacturer')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select class="form-select form-select-sm" wire:model="form.category">
                                    <option value="">Select Category</option>
                                    <option value="Narrow-body">Narrow-body</option>
                                    <option value="Wide-body">Wide-body</option>
                                    <option value="Regional">Regional</option>
                                </select>
                                @error('form.category')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Passengers</label>
                                <input type="number" class="form-control form-control-sm" wire:model="form.max_passengers">
                                @error('form.max_passengers')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Empty Weight (kg)</label>
                                <input type="number" class="form-control form-control-sm" wire:model="form.empty_weight">
                                @error('form.empty_weight')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Zero Fuel Weight (kg)</label>
                                <input type="number" class="form-control form-control-sm" wire:model="form.max_zero_fuel_weight">
                                @error('form.max_zero_fuel_weight')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Takeoff Weight (kg)</label>
                                <input type="number" class="form-control form-control-sm" wire:model="form.max_takeoff_weight">
                                @error('form.max_takeoff_weight')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Landing Weight (kg)</label>
                                <input type="number" class="form-control form-control-sm" wire:model="form.max_landing_weight">
                                @error('form.max_landing_weight')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cargo Capacity (kg)</label>
                                <input type="number" class="form-control form-control-sm" wire:model="form.cargo_capacity">
                                @error('form.cargo_capacity')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Fuel Capacity (L)</label>
                                <input type="number" class="form-control form-control-sm" wire:model="form.max_fuel_capacity">
                                @error('form.max_fuel_capacity')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Deck Crew</label>
                                <input type="number" class="form-control form-control-sm" wire:model="form.max_deck_crew">
                                @error('form.max_deck_crew')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Cabin Crew</label>
                                <input type="number" class="form-control form-control-sm" wire:model="form.max_cabin_crew">
                                @error('form.max_cabin_crew')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">Save Aircraft Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add to modal form -->
    @if ($showForm && $selectedAirlineId)
        <div class="card-body alert alert-info alert-sm text-center" style="border-radius: 0">
            New aircraft type will be automatically associated with {{ $selectedAirline->name }}.
        </div>
    @endif

    @script
        <script>
            $wire.on('aircraft-type-saved', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('aircraftTypeFormModal'));
                modal.hide();
            });
        </script>
    @endscript
</div>
