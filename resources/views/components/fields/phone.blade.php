@props([
    'id',
    'label' => 'Phone Number',
    'required' => false,
])

@php
    $countryCodes = [
        ['code' => '+1', 'name' => 'United States'],
        ['code' => '+44', 'name' => 'United Kingdom'],
        ['code' => '+1', 'name' => 'Canada'],
        ['code' => '+61', 'name' => 'Australia'],
        ['code' => '+91', 'name' => 'India'],
        ['code' => '+49', 'name' => 'Germany'],
        ['code' => '+33', 'name' => 'France'],
        ['code' => '+39', 'name' => 'Italy'],
        ['code' => '+34', 'name' => 'Spain'],
        ['code' => '+86', 'name' => 'China'],
        ['code' => '+81', 'name' => 'Japan'],
        ['code' => '+55', 'name' => 'Brazil'],
        ['code' => '+7',  'name' => 'Russia'],
        ['code' => '+52', 'name' => 'Mexico'],
        ['code' => '+27', 'name' => 'South Africa'],
        ['code' => '+234', 'name' => 'Nigeria'],
        ['code' => '+20', 'name' => 'Egypt'],
        ['code' => '+254', 'name' => 'Kenya'],
        ['code' => '+92', 'name' => 'Pakistan'],
        ['code' => '+880', 'name' => 'Bangladesh'],
    ];
@endphp

<div class="flex flex-col field">
    <label for="{{ $id }}" class="floating-label relative">
        <div class="flex w-full">
            {{-- Country Code Selector --}}
            <select
                name="{{ $id }}_code"
                id="{{ $id }}_code"
                aria-label="Country Code"
                class="select select-bordered input-lg rounded-r-none max-w-[90px]"
                wire:model="{{ $attributes->get('wire:model') }}_code"
                @if($required) required @endif
            >
                <option value="" disabled selected>Please select</option>
                @foreach($countryCodes as $country)
                    <option value="{{ $country['code'] }}" @if($country['code'] === '+44') selected @endif>
                        {{ $country['code'] }}
                    </option>
                @endforeach
            </select>

            {{-- Phone Number Input --}}
            <input
                id="{{ $id }}"
                name="{{ $id }}"
                type="tel"
                placeholder="{{ $label }}"
                aria-label="{{ $label }}"
                wire:change="validateField('{{ $id }}')"
                @if($required) required @endif
                {{ $attributes->except('options')->merge([
                    'class' => 'input input-bordered input-lg w-full rounded-l-none outline-none border-l-0 ' .
                        ($errors->has($attributes->get('wire:model')) ? 'input-error' : '')
                ]) }}
            />
        </div>
        <span>{{ $label }} {!! $required ? '<span class="text-error ml-2">*</span>' : '' !!}</span>
    </label>

    @error($attributes->get('wire:model'))
    <span class="text-error text-sm mt-1">{{ $message }}</span>
    @enderror
</div>
