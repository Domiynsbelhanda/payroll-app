<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalarySettingResource\Pages;
use App\Filament\Resources\SalarySettingResource\RelationManagers;
use App\Models\SalarySetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalarySettingResource extends Resource
{
    protected static ?string $model = SalarySetting::class;

    protected static ?string $navigationGroup = 'Paie';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Rémunération';
    protected static ?string $pluralModelLabel = 'Taux de rémunération';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->label('Employé')
                    ->options(
                        \App\Models\Employee::all()->pluck('full_name', 'id')
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('salary_type')
                    ->label('Type de salaire')
                    ->options([
                        'fixed' => 'Fixe (mensuel)',
                        'hourly' => 'Horaire',
                        'daily' => 'Journalier',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label('Montant')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('bonus')
                    ->label('Bonus')
                    ->numeric()
                    ->default(0),

                Forms\Components\TextInput::make('overtime_rate')
                    ->label('Taux Heures Sup')
                    ->numeric()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')->label('Employé'),
                Tables\Columns\TextColumn::make('salary_type')->label('Type'),
                Tables\Columns\TextColumn::make('amount')->label('Montant'),
                Tables\Columns\TextColumn::make('bonus')->label('Bonus'),
                Tables\Columns\TextColumn::make('overtime_rate')->label('Taux Heures Sup')->suffix('%'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSalarySettings::route('/'),
            'create' => Pages\CreateSalarySetting::route('/create'),
            'edit' => Pages\EditSalarySetting::route('/{record}/edit'),
        ];
    }
}
