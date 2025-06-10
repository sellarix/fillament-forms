<?php

namespace Sellarix\Forms\Livewire\MultiStep;

use Livewire\Attributes\On;
use Livewire\Component;

class PreviousButton extends Component
{
    public string $formSlug;
    public ?int $currentStep;
    public ?int $totalSteps;

    public bool $complete = false;

    protected $listeners = [];

    public function mount(string $formSlug)
    {
        $this->formSlug = $formSlug;

        $this->dispatch("request.{$this->formSlug}");
    }



    #[On('form-updated.{formSlug}')]
    public function handleStepUpdate($payload): void
    {
        $this->currentStep = $payload['step'];
        $this->totalSteps = $payload['total'];
        $this->complete = $payload['complete'];
    }

    public function click(): void
    {
        if ($this->currentStep > 1) {
            $this->dispatch("previousStep.{$this->formSlug}");
        }
    }

    public function render()
    {
        return view('forms::livewire.buttons.previous-button');
    }

}
