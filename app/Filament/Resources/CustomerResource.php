<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Password;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Details')
                    ->icon('heroicon-o-identification')
                    ->columns(4)
                    ->aside()
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->columnSpan(2)
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('last_name')
                            ->columnSpan(2)
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->columnSpan(3)
                            ->required()
                            ->email()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Toggle::make('active')
                            ->columnSpan(1)
                            ->inline(false)
                            ->default(true)
                            ->required()
                            ->rule('boolean'),
                    ]),

                Forms\Components\Section::make(fn (Page $livewire) => self::passwordSectionHeading($livewire))
                    ->icon('heroicon-o-key')
                    ->description(fn (Page $livewire) => self::passwordSectionDescription($livewire))
                    ->aside()
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->rule(Password::default()),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->requiredWith('password')
                            ->same('password')
                            ->validationMessages([
                                'same' => 'The password confirmation does not match.',
                                'required_with' => 'The password confirmation is required with the password.',
                            ])
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('last_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('active')->boolean()->alignCenter(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')->query(fn (Builder $query): Builder => $query->where('active', true)),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    private static function passwordSectionHeading(Page $page): string
    {
        return $page instanceof Pages\CreateCustomer ? 'Set Password' : 'Change Password';
    }

    private static function passwordSectionDescription(Page $page): string
    {
        if ($page instanceof Pages\EditCustomer) {
            return 'A password will only be updated when a new one is entered. Leaving these fields empty will not overwrite the current password.';
        }

        return '';
    }
}
