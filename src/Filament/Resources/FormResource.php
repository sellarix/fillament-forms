<?php

namespace Sellarix\Forms\Filament\Resources;

use Closure;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Illuminate\Support\Str;
use Sellarix\Forms\Filament\Resources\FormResource\Pages;

use Filament\Forms\Components\{Grid, Hidden, Wizard, Wizard\Step, TextInput, Textarea, Toggle, Select, Repeater};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\{EditAction, DeleteAction};
use Filament\Tables\Table as FilamentTable;
use Sellarix\Forms\Filament\Resources\FormResource\RelationManagers\FormSubmissionsRelationManager;
use Sellarix\Forms\Models\Form as FormModel;
use Sellarix\Forms\Enums\FormTheme;
use Sellarix\Forms\Enums\FormStatus;
use Sellarix\Forms\Enums\FieldType;
use Sellarix\Forms\Enums\EmailTemplateMode;
use Sellarix\Forms\Models\FormField;


class FormResource extends Resource
{
    protected static ?string $model = FormModel::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Forms';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                Step::make('General')
                    ->schema(static::formGeneralFields())->columns(2),
                Step::make('Fields')
                    ->schema([
                        static::fieldRepeater(),
                    ]),
                Step::make('Notifications')
                    ->schema(static::formNotificationFields()),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('mode')->badge(),
                TextColumn::make('status')->badge(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }

    protected static function formGeneralFields(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255)->columnSpan(1)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                    if (! filled($get('slug')) && filled($state)) {
                        $set('slug', Str::slug($state));
                    }
                })->live(onBlur: true),
            TextInput::make('slug')
                ->required()
                ->disabled()
                ->dehydrated()
                ->unique(ignoreRecord: true)
                ->maxLength(255)->columnSpan(1),
            Textarea::make('description')
                ->rows(3)->columnSpan(2),

            Select::make('mode')
                ->label('Form Type')
                ->options(FormTheme::options())
                ->required()
                ->reactive(),

            TextInput::make('view')
                ->label('Blade View Override (optional)')
                ->placeholder('e.g. forms.contact.custom')
                ->helperText('Leave blank to use default layout.')
                ->nullable()
                ->visible(fn (callable $get) => filled($get('mode'))),

            Select::make('status')
                ->options(FormStatus::options())
                ->required(),
            Toggle::make('email_to_user')
                ->label('Send confirmation email to user?')
                ->default(false),
        ];
    }


    protected static function fieldForm() {
        return [
            TextInput::make('label')->required(),
            TextInput::make('name')
                ->label('Field ID')
                ->required()
                ->unique(ignoreRecord: true)
                ->reactive()
                ->afterStateUpdated(function (Set $set, $state) {
                    $set('name', str($state)->lower()->slug('_'));
                }),
            TextInput::make('placeholder'),
            TextInput::make('default'),

            Select::make('type')
                ->options(FieldType::options())
                ->required()
                ->reactive(),


            Repeater::make('options')
                ->label('Options')
                ->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('value')->required(),
                ])
                ->visible(fn (callable $get) => in_array($get('type'), ['select', 'radio', 'checkbox']))
                ->default([])
                ->collapsible()
                ->reorderable(),
        ];
    }

    protected static function fieldRepeater(): Repeater
    {
        return Repeater::make('groups')
            ->relationship('groups')
            ->collapsible()
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (! filled($get('slug')) && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            })->live(onBlur: true),

                        TextInput::make('subtitle')->nullable(),

                        Hidden::make('slug')
                            ->disabled()
                            ->dehydrated(),
                    ]),


                Repeater::make('fields')
                    ->relationship('fields')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Select::make('form_field_id')
                            ->columnSpanFull()
                            ->relationship('field', 'label')
                            ->searchable()
                            ->createOptionForm(self::fieldForm())
                            ->editOptionForm(self::fieldForm()),

                            Toggle::make('required')->default(false)->columnSpan(1)->inline(false),
                            TextInput::make('colspan')->numeric()->default(0)->columnSpan(1),

                        ],
                    )
                    ->columns(2)
                    ->columnSpanFull()
                    ->itemLabel(fn (array $state) => FormField::find($state['form_field_id'])->name ?? 'Field')
                    ->orderColumn('sort_order'),

                Toggle::make('has_dependency')
                    ->label('Has Dependency?')
                    ->visible(fn (callable $get) => $get('../../mode') === 'multi')
                    ->reactive(),

                Select::make('depends_on_field')
                    ->label('Depends on Field')
                    ->options(function (callable $get) {
                        $fields = collect($get('../../fields') ?? []);
                        return $fields
                            ->filter(fn ($field) => in_array($field['type'] ?? null, ['select', 'radio', 'checkbox']))
                            ->mapWithKeys(fn ($field) => [$field['name'] => $field['label'] ?? $field['name']]);
                    })
                    ->visible(fn (callable $get) => $get('has_dependency') === true)
                    ->requiredIf('has_dependency', true)
                    ->reactive(),

                Select::make('depends_on_value')
                    ->label('Show when value equals')
                    ->options(function (callable $get) {
                        $fields = collect($get('../../fields') ?? []);
                        $match = $fields->firstWhere('name', $get('depends_on_field'));
                        return collect($match['options'] ?? [])
                            ->mapWithKeys(fn ($option) => [$option['value'] => $option['label'] ?? $option['value']]);
                    })
                    ->visible(fn (callable $get) => filled($get('depends_on_field')))
                    ->requiredIf('has_dependency', true)
                    ->reactive()
            ])
            ->itemLabel(fn (array $state) => $state['title'] ?? 'Group')
            ->orderColumn('sort_order');
    }

    protected static function formNotificationFields(): array
    {
        return [
            TextInput::make('email_template')
                ->label('Custom Email View Path')
                ->placeholder('e.g. mail.contact-confirmation'),
        ];
    }

    public static function getRelations(): array
    {
        return [
           FormSubmissionsRelationManager::class
        ];
    }
}
