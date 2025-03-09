<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Enums;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company')
                    ->maxLength(255),

                Forms\Components\TextInput::make('line_1')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('line_2')
                    ->maxLength(255),

                Forms\Components\TextInput::make('line_3')
                    ->maxLength(255),

                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('region')
                    ->maxLength(255),

                Forms\Components\TextInput::make('postal_code')
                    ->required()
                    ->maxLength(16)
                    ->rules([
                        'regex:/^[A-Z0-9\s\-]+$/',
                    ])
                    ->validationMessages([
                        'regex' => 'The :attribute must only contain letters, numbers, spaces and dashes',
                    ]),

                Forms\Components\Select::make('country_code')
                    ->label('Country')
                    ->required()
                    ->relationship(name: 'country', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Enums\Country::collect()->sort())
                    ->optionsLimit(Enums\Country::count()),

                Forms\Components\Select::make('type')
                    ->label('Address Type')
                    ->required()
                    ->native(false)
                    ->options(Enums\AddressType::class),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('line_1')
            ->columns([
                Tables\Columns\TextColumn::make('company'),
                Tables\Columns\TextColumn::make('line_1'),
                Tables\Columns\TextColumn::make('line_2'),
                Tables\Columns\TextColumn::make('line_3'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('region'),
                Tables\Columns\TextColumn::make('postal_code'),
                Tables\Columns\TextColumn::make('country.name'),
                Tables\Columns\TextColumn::make('type')->badge(),
            ])
            ->defaultPaginationPageOption(5)
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
