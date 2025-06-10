<?php

namespace Sellarix\Forms\Filament\Resources\FormResource\RelationManagers;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class FormSubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    protected static ?string $title = 'Submissions';



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\MarkdownEditor::make('content'),
                // ...
            ]);
    }


    public  function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\TextEntry::make('submitted_at'),
                Components\TextEntry::make('ip_address'),
                Components\RepeatableEntry::make('values')
                    ->label('Submitted Fields')
                    ->schema([
                        Components\TextEntry::make('field.label')
                            ->label('Field'),

                        Components\TextEntry::make('value')
                            ->label('Response'),
                    ])->columns(1)->grid(2)->columnSpanFull(),
            ]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with(['values.field']);
            })
            ->columns([
                TextColumn::make('submitted_at')->since()->label('Submitted'),
                TextColumn::make('ip_address')->label('IP Address'),
            ])
            ->actions([
                ViewAction::make()
                    ->slideOver()
            ]);
    }
}
