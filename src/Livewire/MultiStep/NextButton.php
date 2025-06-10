<?php

namespace Sellarix\Forms\Livewire\MultiStep;

use Livewire\Attributes\On;
use Livewire\Component;

class NextButton extends Component
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
        if ($this->currentStep < $this->totalSteps) {
            $this->dispatch("nextStep.{$this->formSlug}");
        } else {
            $this->dispatch("submit.{$this->formSlug}");
        }
    }

    public function render()
    {
        return view('forms::livewire.buttons.next-button');
    }

}
