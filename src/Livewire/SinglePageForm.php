<?php

namespace Sellarix\Forms\Livewire;

use Filament\Forms\Components\Field;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;
use Sellarix\Forms\Contracts\FormSubmissionRepositoryInterface;
use Sellarix\Forms\DTO\FormDTO;
use Sellarix\Forms\DTO\FormSubmissionDTO;
use Sellarix\Forms\Enums\FieldType;
use Sellarix\Forms\Events\FormSubmitted;

class SinglePageForm extends Component
{
    public ?FormDTO $form;

    public array $state = [
        'honeypot' => ""
    ];

    public array $rules = [
        'state.honeypot' => ['prohibited'],
    ];

    public array $messages = [];

    public string $formSlug;

    public bool $thanks = false;

    public string $thankYouContent;

    public function mount(?FormDTO $form = null, $thankYouContent = null): void
    {
        $this->form = $form;

        $this->formSlug = $form->slug;
        $this->thankYouContent = $thankYouContent;

        $rules = [];
        $messages = [];

        foreach ($this->form->groups as $group) {
            foreach ($group->fields as $field) {
                $this->state[$field->name] = null;
                $rules["state.{$field->name}"] = $field->required ? ['required'] : ['nullable'];
                $messages["state.{$field->name}.required"] = "We need to know your {$field->label}";

                // Optional: Add type-specific rules here
                if ($field->type === FieldType::Email->value) {
                    $rules["state.{$field->name}"][] = 'email:rfc,dns';
                    $messages["state.{$field->name}.email"] = "You {$field->label} must be genuine like test@test.com ";
                }

                if ($field->type === 'number') {
                    $rules["state.{$field->name}"][] = 'numeric';
                }

                if ($field->type === 'compliance' && $field->required) {
                    $rules["state.{$field->name}"][] = 'accepted';
                    $messages["state.{$field->name}.accepted"] = "We need to know you to confirm consent";
                }

                if ($field->type === FieldType::Phone->value) {
                    $this->state[$field->name.'_code'] = null;
                }

                if ($field->type == FieldType::Checkbox->value) {
                    $this->state[$field->name] = [];
                }

            }
        }



        $this->rules = $rules;
        $this->messages = $messages;

        // Set default state values if needed



    }

    /**
     * @throws ValidationException
     */
    public function validateField($field): void
    {
        $key = 'state.'.$field;
        $this->validateOnly($key, $this->rules, $this->messages );
    }

    #[On('submit.{formSlug}')]
    public function submit(): void
    {
        $validated = $this->validate($this->rules, $this->messages);

        /** @var FormSubmissionRepositoryInterface $repository */
        $repository = app(FormSubmissionRepositoryInterface::class);

        $ip = Request::ip();
        $userAgent = Request::userAgent();
        $utm =  [];

        // Map field name => id
        $fieldMap = collect($this->form->groups)
            ->flatMap(fn ($group) => collect($group->fields)
                ->mapWithKeys(fn ($field) => [$field->name => $field]));

        // Filter values: only those that match a field
        $values = collect($validated['state'])
            ->filter(fn ($_, $key) => $fieldMap->has($key))
            ->mapWithKeys(fn ($value, $name) => [$fieldMap[$name]->id => is_array($value) ? json_encode($value) : $value])
            ->toArray();

        $submission = new FormSubmissionDTO(
            formId: $this->form->id,
            userId: auth()->id(),
            ipAddress: $ip,
            userAgent: $userAgent,
            values: $values,
            metadata: $utm
        );

        $repository->store($submission);

        $this->dispatch('form_submitted_success', data: [
            'form' => $this->form->slug,
            'dataLayer' => [
                'event' => "{$this->form->slug}_submitted_success",
                'data' => $validated,
            ],
        ]);

        FormSubmitted::dispatch($submission,  $this->form);

        session()->flash('success', 'Form submitted successfully.');

        $this->thanks = true;

        $this->reset('state');

        $this->dispatchStepEvent();
    }


    public function dispatchStepEvent(): void {
        //
    }



    public function render(): View
    {
        if (! $this->form) {
            return view('forms::livewire.form-not-found');
        }

        return view('forms::livewire.single-page-form',[
            'form' => $this->form,
        ] );
    }
}
