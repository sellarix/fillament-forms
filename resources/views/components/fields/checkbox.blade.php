@props([
    'id',
    'label',
    'options' => [], // [['label' => 'Apple', 'value' => 'apple'], ...]
    'required' => false,
])

<div class="field">
    <h4 class="text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-error ml-1">*</span>
        @endif
    </h4>

    <div class="flex flex-col space-y-2">
        @foreach ($options as $index => $option)
            <label class="inline-flex items-center space-x-2">
                <input
                    type="checkbox"
                    name="{{ $id }}[]"
                    value="{{ $option->value }}"
                    wire:model="{{ $attributes->get('wire:model') }}"
                    wire:change="validateField('{{ $id }}')"
                    class="checkbox checkbox-primary"
                    wire:key="{{ $id . '_' . $index }}"
                />
                <span>{{ $option->label }}</span>
            </label>
        @endforeach
    </div>

    @error($attributes->get('wire:model'))
        <span class="text-error text-sm mt-1">{{ $message }}</span>
    @enderror
</div>
