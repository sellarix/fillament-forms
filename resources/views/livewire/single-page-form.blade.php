<div class="space-y-6" wire:key="'form_'.{{$form->slug}}" id="'form_'.{{$form->slug}}">

    @if($thanks == false)
        {{-- resources/views/vendor/forms/livewire/single-page.blade.php --}}
        <form wire:submit.prevent="submit" class="space-y-6">

        @foreach ($form->groups as $group)
            <fieldset class=" rounded-md" wire:key="{{$form->slug}}_{{$group->slug}}">

                <div class="form-header mb-4">
                    <legend class="text-lg font-semibold mb-0">{{ $group->title }}</legend>
                    @if($group->subtitle)
                        <p>{{$group->subtitle}}</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @foreach ($group->fields as $field)
                        <!-- col-span-1 col-span-2" -->
                        <div class="mt-2 col-span-{{$field->colSpan == 0 ? 2 : $field->colSpan}}">

                            <x-dynamic-component
                                :component="'forms::fields.' . $field->type"
                                :id="$field->name"
                                :label="$field->label"
                                :required="$field->required"
                                :name="$field->name"
                                :wire:model="'state.' . $field->name"
                                :wire:key="$form->slug.'_'.$field->name"
                                :options="$field->options"
                            />
                        </div>
                    @endforeach
                    <label for="honeypot" class="hidden">Honeypot
                        <input type="hidden" wire:model="state.honeypot" name="honeypot" id="honeypot" value="" />
                    </label>
                </div>
            </fieldset>
        @endforeach

        <div class="pt-1">
            <button class="btn btn-lg btn-primary" type="submit">
                Submit
            </button>
        </div>
    </form>

    @else
        <div class="mb-8 flex justify-center">
            <div class="w-20 h-20 rounded-full bg-primary-100 flex items-center justify-center">
                <i class="fa-solid fa-check text-primary-600 text-4xl"></i>
            </div>
        </div>

        {!! $thankYouContent !!}

        <a class="btn btn-lg btn-primary" title="Return to Cohesii Spaces" href="/">Return to Homepage</a>
    @endif
</div>
