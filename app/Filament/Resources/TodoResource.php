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
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;

class TodoResource extends Resource
{
    protected static ?string $model = Todo::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Todo 列表';
    protected static ?string $navigationLabel = '任務管理';
    protected static ?string $modelLabel = '任務';
    protected static ?string $pluralModelLabel = '任務';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('任務資訊')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('標題')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('描述')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Flatpickr::make('due_date')
                            ->label('截止時間')
                            ->dateFormat('Y-m-d H:i')
                            ->allowInput()
                            ->altInput(true)
                            ->altFormat('Y-m-d H:i')
                            ->customConfig([
                                'locale' => 'zh_tw',
                            ]),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('任務細節')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->label('分類'),
                        Forms\Components\Select::make('priority_id')
                            ->relationship('priority', 'name')
                            ->searchable()
                            ->preload()
                            ->label('優先級'),
                        Forms\Components\Select::make('status_id')
                            ->relationship('status', 'name')
                            ->searchable()
                            ->preload()
                            ->label('狀態'),
                        Forms\Components\Toggle::make('is_completed')
                            ->label('已完成'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('指派資訊')
                    ->schema([
                        Forms\Components\Select::make('created_by')
                            ->relationship('creator', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('建立者'),
                        Forms\Components\Select::make('assigned_to')
                            ->relationship('assignee', 'name')
                            ->searchable()
                            ->preload()
                            ->label('指派給'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('標題')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('分類')
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
                    ->label('優先級')
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
                    ->label('狀態')
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
                    ->label('建立者'),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->sortable()
                    ->label('指派給'),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('截止時間')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record) => $record->due_date && $record->due_date->isPast() && !$record->is_completed ? 'danger' : 'gray'),
                Tables\Columns\IconColumn::make('is_completed')
                    ->label('已完成')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('分類')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('priority')
                    ->label('優先級')
                    ->relationship('priority', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('狀態')
                    ->relationship('status', 'name'),
                Tables\Filters\SelectFilter::make('creator')
                    ->relationship('creator', 'name')
                    ->label('建立者'),
                Tables\Filters\SelectFilter::make('assignee')
                    ->relationship('assignee', 'name')
                    ->label('指派給'),
                Tables\Filters\TernaryFilter::make('is_completed')
                    ->label('已完成'),
                Tables\Filters\Filter::make('overdue')
                    ->label('已逾期')
                    ->query(fn (Builder $query): Builder => $query->where('due_date', '<', now())->where('is_completed', false)),
                Tables\Filters\Filter::make('due_today')
                    ->label('今天到期')
                    ->query(fn (Builder $query): Builder => $query->whereDate('due_date', today())->where('is_completed', false)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('查看'),
                Tables\Actions\EditAction::make()->label('編輯'),
                Tables\Actions\DeleteAction::make()->label('刪除'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('批次刪除'),
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
