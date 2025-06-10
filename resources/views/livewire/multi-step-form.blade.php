<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-6 md:p-8 space-y-6" id="form_{{$form->slug}}">

    @if($thanks == false)
        <form wire:submit.prevent="submit" class="space-y-6">
        @php
            $stepGroup = $form->groups[$this->currentStep - 1] ?? null;
        @endphp
        @if ($stepGroup)
            <fieldset class="p-4 rounded-md" wire:key="{{$form->slug}}_{{$stepGroup->slug}}">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-800">{{$stepGroup->title}}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{$stepGroup->subtitle}}</p>
                </div>
                <div  class="grid grid-cols-2 gap-4">


                    @foreach ($stepGroup->fields as $field)
                        <!-- md:col-span-1 md-col-span-2" -->
                        <div class="mt-2 col-span-2 md:col-span-{{$field->colSpan == 0 ? 2 : $field->colSpan}} ">
                            <x-dynamic-component
                                :component="'forms::fields.' . $field->type"
                                :id="$field->name"
                                :label="$field->label"
                                :required="$field->required"
                                :name="$field->name"
                                :wire:model="'state.' . $field->name"
                                :wire:key="$form->slug.'_'.$field->name"
                                :options="$field->options "
                            />
                        </div>
                    @endforeach
                </div>
            </fieldset>
        @endif

        <label for="honeypot" class="hidden">Honeypot
            <input type="hidden" wire:model="state.honeypot" name="honeypot" id="honeypot" value="" />
        </label>
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
