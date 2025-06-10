<?php

namespace Sellarix\Forms\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Sellarix\Forms\DTO\FormDTO;
use Sellarix\Forms\DTO\FormSubmissionDTO;


class FormSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public FormSubmissionDTO $submission,
        public FormDTO $form
    ) {}
}
