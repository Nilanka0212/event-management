@php
    $servicePrefix = $config['service_prefix'] ?? null;
    $chargePrefix = $config['charge_prefix'] ?? null;
    $serviceRows = $servicePrefix ? old($servicePrefix, $config['existing_services'] ?? []) : [];
    $chargeRows = $chargePrefix ? old($chargePrefix, $config['existing_charges'] ?? []) : [];
    if ($servicePrefix && count($serviceRows) === 0) $serviceRows = [[]];
    if ($chargePrefix && count($chargeRows) === 0) $chargeRows = [[]];
    $serviceMode = $config['service_mode'] ?? 'sale';
@endphp

@if($servicePrefix)
    <div class="col-12 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-stars text-primary me-1"></i> {{ $config['service_title'] ?? 'Services' }}</h6>
            <button class="btn btn-sm btn-light-gray" type="button" data-add-line>
                <i class="bi bi-plus-lg me-1"></i> + Add service
            </button>
        </div>
        <div data-line-group data-prefix="{{ $servicePrefix }}" data-next-index="{{ count($serviceRows) }}">
            @foreach($serviceRows as $index => $row)
                <div class="row g-2 align-items-end mb-2 border-bottom pb-2" data-line-row>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label small mb-1">Service</label>
                        <select name="{{ $servicePrefix }}[{{ $index }}][service_id]" class="form-select form-select-sm" data-service-select>
                            <option value="">Custom / select service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-cost="{{ $service->cost_price }}" @selected((string) data_get($row, 'service_id') === (string) $service->id)>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($serviceMode !== 'cost')
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label small mb-1">Description</label>
                            <input name="{{ $servicePrefix }}[{{ $index }}][description]" class="form-control form-control-sm" value="{{ data_get($row, 'description') }}" data-service-description placeholder="Service details">
                        </div>
                    @endif
                    <div class="col-5 col-sm-4 col-lg-2">
                        <label class="form-label small mb-1">Qty</label>
                        <input name="{{ $servicePrefix }}[{{ $index }}][qty]" type="number" min="1" step="1" class="form-control form-control-sm" value="{{ data_get($row, 'qty', 1) }}">
                    </div>
                    <div class="col-5 col-sm-4 col-lg-2">
                        <label class="form-label small mb-1">{{ $serviceMode === 'cost' ? 'Cost price' : 'Unit price' }}</label>
                        <input name="{{ $servicePrefix }}[{{ $index }}][{{ $serviceMode === 'cost' ? 'cost_price' : 'unit_price' }}]" type="number" min="0" step="0.01" class="form-control form-control-sm" value="{{ data_get($row, $serviceMode === 'cost' ? 'cost_price' : 'unit_price') }}" data-service-price placeholder="0.00">
                    </div>
                    <div class="col-2 col-sm-4 col-lg-1 d-flex align-items-end justify-content-center pb-1">
                        <button type="button" class="btn-action btn-action-delete w-100" data-remove-line title="Remove service">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if($chargePrefix)
    <div class="col-12 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-wallet2 text-primary me-1"></i> Other charges</h6>
            <button class="btn btn-sm btn-light-gray" type="button" data-add-line>
                <i class="bi bi-plus-lg me-1"></i> + Add charge
            </button>
        </div>
        <div data-line-group data-prefix="{{ $chargePrefix }}" data-next-index="{{ count($chargeRows) }}">
            @foreach($chargeRows as $index => $row)
                <div class="row g-2 align-items-end mb-2 border-bottom pb-2" data-line-row>
                    <div class="col-12 col-md-8">
                        <label class="form-label small mb-1">Description</label>
                        <input name="{{ $chargePrefix }}[{{ $index }}][description]" class="form-control form-control-sm" value="{{ data_get($row, 'description') }}" placeholder="e.g. Transport or setup fee">
                    </div>
                    <div class="col-9 col-md-3">
                        <label class="form-label small mb-1">Amount</label>
                        <input name="{{ $chargePrefix }}[{{ $index }}][amount]" type="number" min="0" step="0.01" class="form-control form-control-sm" value="{{ data_get($row, 'amount') }}" placeholder="0.00">
                    </div>
                    <div class="col-3 col-md-1 d-flex align-items-end justify-content-center pb-1">
                        <button type="button" class="btn-action btn-action-delete w-100" data-remove-line title="Remove charge">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@once
    @push('scripts')
    <script>
        document.addEventListener('click', event => {
            const addButton = event.target.closest('[data-add-line]');
            if (addButton) {
                const group = addButton.parentElement.nextElementSibling;
                const template = group.querySelector('[data-line-row]');
                const nextIndex = Number(group.dataset.nextIndex || group.children.length);
                const row = template.cloneNode(true);
                row.querySelectorAll('[name]').forEach(field => {
                    field.name = field.name.replace(/\[\d+\]/, `[${nextIndex}]`);
                    if (field.tagName === 'SELECT') field.selectedIndex = 0;
                    else if (field.type === 'number' && field.name.endsWith('[qty]')) field.value = 1;
                    else field.value = '';
                });
                group.append(row);
                group.dataset.nextIndex = nextIndex + 1;
            }

            const removeButton = event.target.closest('[data-remove-line]');
            if (removeButton) {
                const group = removeButton.closest('[data-line-group]');
                const row = removeButton.closest('[data-line-row]');
                if (group.children.length > 1) row.remove();
                else row.querySelectorAll('input').forEach(field => field.value = field.name.endsWith('[qty]') ? 1 : '');
            }
        });

        document.addEventListener('change', event => {
            if (!event.target.matches('[data-service-select]')) return;
            const row = event.target.closest('[data-line-row]');
            const option = event.target.selectedOptions[0];
            const description = row.querySelector('[data-service-description]');
            const price = row.querySelector('[data-service-price]');
            if (description && !description.value) description.value = option.textContent.trim();
            if (price && option.dataset.cost && !price.value) price.value = option.dataset.cost;
        });
    </script>
    @endpush
@endonce