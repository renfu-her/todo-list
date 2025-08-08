<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriorityResource\Pages;
use App\Filament\Resources\PriorityResource\RelationManagers;
use App\Models\Priority;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PriorityResource extends Resource
{
    protected static ?string $model = Priority::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Task Management';
    protected static ?string $navigationLabel = '優先級管理';
    protected static ?string $modelLabel = '優先級';
    protected static ?string $pluralModelLabel = '優先級';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Priority Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\ColorPicker::make('color')
                            ->required()
                            ->default('#EF4444'),
                        Forms\Components\Select::make('level')
                            ->options([
                                1 => 'Low',
                                2 => 'Medium',
                                3 => 'High',
                                4 => 'Urgent',
                            ])
                            ->required()
                            ->default(1),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->sortable(),
                Tables\Columns\TextColumn::make('todos_count')
                    ->counts('todos')
                    ->sortable()
                    ->label('Tasks'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->options([
                        1 => 'Low',
                        2 => 'Medium',
                        3 => 'High',
                        4 => 'Urgent',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('level', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TodosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPriorities::route('/'),
            'create' => Pages\CreatePriority::route('/create'),
            'edit' => Pages\EditPriority::route('/{record}/edit'),
        ];
    }
}
