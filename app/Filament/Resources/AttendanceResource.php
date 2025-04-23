<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Présences';
    protected static ?string $pluralModelLabel = 'Gestion des présences';
    protected static ?string $navigationGroup = 'Présence & Temps';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->label('Employé')
                    ->relationship('employee', 'full_name')
                    ->searchable()
                    ->required(),

                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->default(now())
                    ->required(),

                Forms\Components\TimePicker::make('check_in')
                    ->label('Heure d’arrivée')
                    ->default('08:00'),

                Forms\Components\TimePicker::make('check_out')
                    ->label('Heure de départ')
                    ->default('16:30'),

                Forms\Components\Select::make('status')
                    ->label('Statut')
                    ->options([
                        'present' => 'Présent',
                        'absent' => 'Absent',
                        'late' => 'En retard',
                        'sick' => 'Malade',
                        'leave' => 'Congé',
                    ])
                    ->default('present')
                    ->required(),

                Forms\Components\TextInput::make('worked_minutes')
                    ->label('Minutes travaillées')
                    ->disabled()
                    ->dehydrated(false), // Ne pas sauvegarder le champ manuellement
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')->label('Employé'),
                Tables\Columns\TextColumn::make('date')->label('Date')->date(),
                Tables\Columns\TextColumn::make('check_in')->label('Arrivée'),
                Tables\Columns\TextColumn::make('check_out')->label('Départ'),
                Tables\Columns\TextColumn::make('worked_minutes')->label('Minutes'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'absent' => 'danger',
                        'late' => 'warning',
                        'sick' => 'gray',
                        'leave' => 'info',
                        default => 'secondary',
                    })
                    ->label('Statut'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                // Filtre par date
                Tables\Filters\Filter::make('date')
                    ->label('Filtrer par jour')
                    ->form([
                        Forms\Components\DatePicker::make('date')
                            ->label('Date')
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['date']) {
                            $query->whereDate('date', $data['date']);
                        }
                    }),

                // Filtre par employé
                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Filtrer par employé')
                    ->relationship('employee', 'id')
                    ->getOptionLabelUsing(fn ($record) => $record->full_name)
                    ->searchable(),

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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
