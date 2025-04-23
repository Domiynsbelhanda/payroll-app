<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Filament\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationGroup = 'Présence & Temps';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Demandes d\'absence';

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

                Forms\Components\Select::make('type')
                    ->label('Type d’absence')
                    ->options([
                        'congé' => 'Congé',
                        'maladie' => 'Maladie',
                        'retard' => 'Retard',
                        'absence' => 'Absence injustifiée',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('reason')
                    ->label('Motif')
                    ->rows(2),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Date début')
                    ->required(),

                Forms\Components\DatePicker::make('end_date')
                    ->label('Date fin')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Statut')
                    ->options([
                        'en attente' => 'En attente',
                        'approuvé' => 'Approuvé',
                        'rejeté' => 'Rejeté',
                    ])
                    ->default('en attente')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')->label('Employé')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('type')->label('Type'),
                Tables\Columns\TextColumn::make('start_date')->label('Début')->date(),
                Tables\Columns\TextColumn::make('end_date')->label('Fin')->date(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'warning' => 'en attente',
                        'success' => 'approuvé',
                        'danger' => 'rejeté',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'en attente' => 'En attente',
                        'approuvé' => 'Approuvé',
                        'rejeté' => 'Rejeté',
                    ])
                    ->label('Filtrer par statut'),

                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Filtrer par employé')
                    ->relationship('employee', 'id') // pas 'full_name' ici
                    ->getOptionLabelUsing(fn ($record) => $record->full_name)
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
