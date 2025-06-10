<?php

namespace Sellarix\Forms;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Sellarix\Forms\Contracts\FormRepositoryInterface;
use Sellarix\Forms\Contracts\FormSubmissionRepositoryInterface;
use Sellarix\Forms\Listener\FormSubmittedListener;
use Sellarix\Forms\Livewire\MultiStep\NextButton;
use Sellarix\Forms\Livewire\MultiStep\PreviousButton;
use Sellarix\Forms\Livewire\MultiStep\Stepper;
use Sellarix\Forms\Livewire\MultiStepForm;
use Sellarix\Forms\Livewire\SinglePageForm;
use Sellarix\Forms\Events\FormSubmitted;
use Sellarix\Forms\Repositories\FormRepository;
use Sellarix\Forms\Repositories\FormSubmissionRepository;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FormsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('forms')
            ->hasConfigFile()
            ->hasViews('forms')
            ->hasMigrations([
                '2024_01_01_000001_create_forms_table',
                '2024_01_01_000002_create_form_fields_table',
                '2024_01_01_000003_create_form_groups_table',
                '2024_01_01_000004_create_form_group_fields_table',
                '2024_01_01_000005_create_form_submissions_table',
                '2024_01_01_000006_create_form_submission_values_table',
            ])
            ->runsMigrations();
    }

    public function packageRegistered(): void
    {
        $this->app->bind(FormRepositoryInterface::class, FormRepository::class);
        $this->app->bind(
            FormSubmissionRepositoryInterface::class,
            FormSubmissionRepository::class,
        );
    }

    public function packageBooted(): void
    {

        Blade::anonymousComponentNamespace('forms::components', 'forms');
        Blade::componentNamespace('Sellarix\\Forms\\View\\Components', 'forms');

        Livewire::component('single-page-form', SinglePageForm::class);
        Livewire::component('multi-step-form', MultiStepForm::class);
        Livewire::component('form-next-button', NextButton::class);
        Livewire::component('form-previous-button', PreviousButton::class);
        Livewire::component('form-stepper', Stepper::class);

        Event::listen(
            FormSubmitted::class,
            FormSubmittedListener::class,
        );
    }
}
