<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TodoResource\Pages;
use App\Filament\Resources\TodoResource\RelationManagers;
use App\Models\Todo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TodoResource extends Resource
{
    protected static ?string $model = Todo::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationGroup = 'Task Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Task Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('due_date')
                            ->label('Due Date'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Task Details')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Category'),
                        Forms\Components\Select::make('priority_id')
                            ->relationship('priority', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Priority'),
                        Forms\Components\Select::make('status_id')
                            ->relationship('status', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Status'),
                        Forms\Components\Toggle::make('is_completed')
                            ->label('Completed'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Assignment')
                    ->schema([
                        Forms\Components\Select::make('created_by')
                            ->relationship('creator', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Created By'),
                        Forms\Components\Select::make('assigned_to')
                            ->relationship('assignee', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Assigned To'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Work' => 'primary',
                        'Personal' => 'success',
                        'Shopping' => 'warning',
                        'Health' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('priority.name')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Urgent' => 'danger',
                        'High' => 'warning',
                        'Medium' => 'info',
                        'Low' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status.name')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Completed' => 'success',
                        'In Progress' => 'primary',
                        'On Hold' => 'warning',
                        'Cancelled' => 'danger',
                        'Pending' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('creator.name')
                    ->sortable()
                    ->label('Created By'),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->sortable()
                    ->label('Assigned To'),
                Tables\Columns\TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record) => $record->due_date && $record->due_date->isPast() && !$record->is_completed ? 'danger' : 'gray'),
                Tables\Columns\IconColumn::make('is_completed')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('priority')
                    ->relationship('priority', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->relationship('status', 'name'),
                Tables\Filters\SelectFilter::make('creator')
                    ->relationship('creator', 'name')
                    ->label('Created By'),
                Tables\Filters\SelectFilter::make('assignee')
                    ->relationship('assignee', 'name')
                    ->label('Assigned To'),
                Tables\Filters\TernaryFilter::make('is_completed'),
                Tables\Filters\Filter::make('overdue')
                    ->query(fn (Builder $query): Builder => $query->where('due_date', '<', now())->where('is_completed', false)),
                Tables\Filters\Filter::make('due_today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('due_date', today())->where('is_completed', false)),
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
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTodos::route('/'),
            'create' => Pages\CreateTodo::route('/create'),
            'view' => Pages\ViewTodo::route('/{record}'),
            'edit' => Pages\EditTodo::route('/{record}/edit'),
        ];
    }
}
