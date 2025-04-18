<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Employés';
    protected static ?string $pluralModelLabel = 'Employés';

    protected static ?string $navigationGroup = 'Employees';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')->label('Prénom')->required(),
                Forms\Components\TextInput::make('middle_name')->label('Post-nom'),
                Forms\Components\TextInput::make('last_name')->label('Nom de famille')->required(),
                Forms\Components\TextInput::make('position')->label('Poste'),
                Forms\Components\Select::make('department_id')
                    ->label('Département')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('contract_type_id')
                    ->label('Type de contrat')
                    ->relationship('contractType', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\FileUpload::make('profile_photo')
                    ->label('Photo de profil')
                    ->directory('employees')
                    ->image()
                    ->imagePreviewHeight('100')
                    ->maxSize(1024)
                    ->preserveFilenames()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->label('Photo')
                    ->disk('public')
                    ->defaultImageUrl(asset('images/profile.png'))
                    ->circular(),
                Tables\Columns\TextColumn::make('first_name')->label('Prénom'),
                Tables\Columns\TextColumn::make('middle_name')->label('Post-nom'),
                Tables\Columns\TextColumn::make('last_name')->label('Nom'),
                Tables\Columns\TextColumn::make('position')->label('Poste'),
                Tables\Columns\TextColumn::make('department.name')->label('Département'),
                Tables\Columns\TextColumn::make('contractType.name')->label('Type de contrat'),
                Tables\Columns\TextColumn::make('created_at')->label('Créé le')->dateTime(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
