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
    protected static ?string $navigationGroup = 'Todo 列表';
    protected static ?string $navigationLabel = '優先級管理';
    protected static ?string $modelLabel = '優先級';
    protected static ?string $pluralModelLabel = '優先級';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('優先級資訊')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('名稱')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\ColorPicker::make('color')
                            ->label('顏色')
                            ->required()
                            ->default('#EF4444'),
                        Forms\Components\Select::make('level')
                            ->label('等級')
                            ->options([
                                1 => '低',
                                2 => '中',
                                3 => '高',
                                4 => '緊急',
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
                    ->label('名稱')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label('顏色')
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('等級')
                    ->sortable(),
                Tables\Columns\TextColumn::make('todos_count')
                    ->counts('todos')
                    ->sortable()
                    ->label('任務數量'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->label('等級')
                    ->options([
                        1 => '低',
                        2 => '中',
                        3 => '高',
                        4 => '緊急',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('編輯'),
                Tables\Actions\DeleteAction::make()->label('刪除'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('批次刪除'),
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
