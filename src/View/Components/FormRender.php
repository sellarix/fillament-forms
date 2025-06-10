<?php

namespace Sellarix\Forms\View\Components;

use Illuminate\View\Component;
use Sellarix\Forms\Contracts\FormRepositoryInterface;

class FormRender extends Component
{
    public function __construct(public string $slug) {}

    public function render()
    {
        $form = app(FormRepositoryInterface::class)->findBySlug($this->slug);

        if (! $form) {
            return view('forms::components.form-not-found');
        }

        $component = match ($form->mode) {
            'multi' => 'multi-step-form',
            default => 'single-page-form',
        };


        return view('forms::components.form-render', ['form' => $form, 'component' => $component]);
    }
}
