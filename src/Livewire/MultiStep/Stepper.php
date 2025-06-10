<?php

namespace Sellarix\Forms\Livewire\MultiStep;

use Livewire\Attributes\On;
use Livewire\Component;

class Stepper extends Component
{
    public string $formSlug;
    public ?int $currentStep;
    public ?int $totalSteps;

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
    }



    public function render()
    {
        return view('forms::livewire.stepper');
    }

}
