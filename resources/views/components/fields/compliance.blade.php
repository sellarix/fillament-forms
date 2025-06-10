@props([
    'id',
    'label',
    'required' => false,
])

<div class="flex flex-col field">
    <label class="label" for="{{$id}}">
        <input
            type="checkbox"
            id="{{ $id }}"
            name="{{ $id }}"
            aria-label="{{ $label }}"
            wire:change="validateField('{{$id}}')"
            @if($required) required @endif
            {{ $attributes ->except('options')->merge([
               'class' => 'checkbox checkbox-md outline-none checkbox-primary ' .
                   ($errors->has($attributes->get('wire:model')) ? ' checkbox-error' : '')
            ]) }}
        />
        <span class="text-wrap pl-2"> {{ $label }}</span>
    </label>
    @error($attributes->get('wire:model'))
        <span class="text-error text-sm mt-1">{{ $message }}</span>
    @enderror
</div>
