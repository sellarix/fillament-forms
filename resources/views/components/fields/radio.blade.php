@props([
    'id',
    'label',
    'options' => [], // [['label' => 'Email', 'value' => 'email'], ...]
    'required' => false,
])

<fieldset class="field">
    <legend class="text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-error ml-1">*</span>
        @endif
    </legend>

    <div class="flex flex-col space-y-2">
        @foreach ($options as $option)
            <label class="inline-flex items-center space-x-2">
                <input
                    type="radio"
                    name="{{ $id }}"
                    value="{{ $option['value'] }}"
                    wire:model="{{ $attributes->get('wire:model') }}"
                    wire:change="validateField('{{ $id }}')"
                    class="radio radio-primary"
                    @if($required) required @endif
                />
                <span>{{ $option['label'] }}</span>
            </label>
        @endforeach
    </div>

    @error($attributes->get('wire:model'))
    <span class="text-error text-sm mt-1">{{ $message }}</span>
    @enderror
</fieldset>
