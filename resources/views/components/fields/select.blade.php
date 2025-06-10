@props([
    'id',
    'label',
    'placeholder' => '',
    'options' => [], // [['label' => 'Option 1', 'value' => 'option1'], ...]
    'required' => false,
    'icon' => null,
])

@php
    $wireModel = $attributes->get('wire:model');
@endphp

<div class="flex flex-col field" x-data="{
    open: false,
    search: '',
    selected: @entangle($wireModel),

    get filteredOptions() {
        if (!this.search) return @js($options);
        return @js($options).filter(option =>
            option.label.toLowerCase().includes(this.search.toLowerCase())
        );
    },

    get displayValue() {
        const option = @js($options).find(opt => opt.value === this.selected);
        return option ? option.label : '';
    },

    selectOption(option) {
        this.selected = option.value;
        this.search = option.label;
        this.open = false;
        $wire.call('validateField', '{{ $id }}');
    }
}">

    <label class="floating-label relative">
        @if ($icon)
            <span class="absolute z-10 inset-y-0 left-3 flex items-center pointer-events-none text-gray-400 !top-0">
                <i class="{{ $icon }}"></i>
            </span>
        @endif



        <input
            id="{{ $id }}"
            type="text"
            x-model="search"
            @click="open = true; search = ''"
            @input="open = true"
            @click.away="open = false; search = displayValue"
            placeholder="{{ $placeholder ?: $label }}"
            @if($required) required @endif
            {{ $attributes->except('options')->merge([
                'class' => 'input input-bordered input-lg w-full outline-none ' .
                    ($icon ? 'pl-10 ' : '') .
                    'pr-10 ' .
                    ($errors->has($wireModel) ? 'input-error' : '')
            ]) }}
        />

        <!-- Dropdown arrow -->
        <div class="absolute z-10 inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>

        <span>{{ $label }} {!! $required ? '<span class="text-error ml-2">*</span>' : '' !!}</span>
    </label>

    <!-- Dropdown with fixed height and scrolling -->
    <div class="relative">
        <div x-show="open"
             x-transition
             class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-[240px] overflow-y-auto"
        >

            <template x-for="option in filteredOptions" :key="option.value">
                <div @click="selectOption(option)"
                     class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-50 last:border-b-0"
                     :class="{ 'bg-blue-50': selected === option.value }">
                    <span x-text="option.label"></span>
                    <span x-show="selected === option.value" class="float-right text-blue-500">✓</span>
                </div>
            </template>

            <div x-show="filteredOptions.length === 0" class="px-4 py-3 text-gray-500 text-center">
                No results found
            </div>
        </div>
    </div>

    @error($wireModel)
    <span class="text-error text-sm mt-1">{{ $message }}</span>
    @enderror
</div>
