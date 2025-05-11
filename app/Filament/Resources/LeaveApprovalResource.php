<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveApprovalResource\Pages;
use App\Filament\Resources\LeaveApprovalResource\RelationManagers;
use App\Models\LeaveApproval;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;

class LeaveApprovalResource extends Resource
{
    protected static ?string $model = LeaveApproval::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('leave_id')
                    ->relationship('leave', 'reason')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('approved_by')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->heading('Leave Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('leave.reason')
                            ->label('Reason'),
                        Infolists\Components\TextEntry::make('leave.user.name')
                            ->label('Employee'),
                        Infolists\Components\TextEntry::make('notes')
                            ->label('Notes')
                            ->columnSpanFull(),
                    ]),

                Actions::make([
                    Action::make('approve_leave')
                        ->action(function (LeaveApproval $record) {
                            $record->update([
                                'status' => 'approved',
                                'approved_by' => auth()->user()->id,
                            ]);

                            Notification::make()
                                ->title('Leave approved')
                                ->success()
                                ->send();
                        }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('leave.reason')
                    ->label('Reason')
                    ->sortable(),
                Tables\Columns\TextColumn::make('leave.user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('leave.start_date')
                    ->label('Start Date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('leave.end_date')
                    ->label('End Date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('approved_by')
                    ->numeric()
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListLeaveApprovals::route('/'),
            'view' => Pages\ViewLeaveApproval::route('/{record}'),
        ];
    }
}
