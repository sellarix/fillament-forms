@props([
    'id',
    'label',
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'icon' => null, // optional Font Awesome class, e.g. "fas fa-user"
])

<div class="flex flex-col field">
    <label for="{{ $id }}" class="floating-label relative">
        <div class="relative w-full">
            @if ($icon)
                <span class="absolute  z-10 inset-y-0 left-3 flex items-center pointer-events-none text-gray-400 !top-0">
                    <i class="{{ $icon }}"></i>
                </span>
            @endif

            <input
                id="{{ $id }}"
                name="{{ $id }}"
                type="{{ $type }}"
                placeholder="{{ $placeholder ?: $label }}"
                aria-label="{{ $label }}"
                aria-placeholder="{{ $placeholder ?: $label }}"
                wire:change="validateField('{{$id}}')"
                @if($required) required @endif
                {{ $attributes->except('options')->merge([
                    'class' => 'input input-bordered input-lg w-full outline-none ' .
                        ($icon ? 'pl-10' : '') .
                        ($errors->has($attributes->get('wire:model')) ? ' input-error' : '')
                ]) }}
            />
        </div>
        <span>{{ $label }} {!! $required ? '<span class="text-error ml-2">*</span>' : '' !!}</span>
    </label>
    @error($attributes->get('wire:model'))
    <span class="text-error text-sm mt-1">{{ $message }}</span>
    @enderror
</div>
