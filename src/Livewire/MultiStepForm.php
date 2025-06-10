<?php

namespace Sellarix\Forms\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Sellarix\Forms\DTO\FormDTO;

class MultiStepForm extends SinglePageForm
{
    public int $currentStep;
    public int $totalSteps;


    public function mount(?FormDTO $form = null, $thankYouContent = null): void
    {
        parent::mount($form, $thankYouContent);

        $this->totalSteps = count($this->form->groups);
        $this->currentStep = 1;

    }

    #[On('request.{formSlug}')]
    public function request(): void
    {
        $this->dispatchStepEvent();
    }

    #[On('nextStep.{formSlug}')]
    public function nextStep(): void
    {
        if ($this->currentStep < $this->totalSteps) {

            $fields = collect($this->form->groups[$this->currentStep - 1]->fields)->pluck('name')->map(fn($name) => "state.$name")->all();
            $stepRules = collect($this->rules)
                ->only($fields)
                ->toArray();

            $this->validate($stepRules, $this->messages);
            $this->currentStep++;
            $this->dispatchStepEvent();
        }
    }

    #[On('previousStep.{formSlug}')]
    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatchStepEvent();
        }
    }

    #[On('goToStep.{formSlug}')]
    public function goToStep(int $step): void
    {
        if ($step >= 1 && $step <= $this->totalSteps) {
            $this->currentStep = $step;
            $this->dispatchStepEvent();
        }
    }

    public function dispatchStepEvent(): void
    {
        $this->dispatch("form-updated.{$this->form->slug}", [
            'step' => $this->currentStep,
            'total' => $this->totalSteps,
            'complete' => $this->thanks,
        ]);
    }

    public function render(): View
    {

        if (! $this->form) {
            return view('forms::livewire.form-not-found');
        }

        return view('forms::livewire.multi-step-form', [
            'form' => $this->form,
            'currentStep' => $this->currentStep,
            'totalSteps' => $this->totalSteps,
            'group' => $this->form->groups[$this->currentStep - 1] ?? null,
        ]);
    }
}
