<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanResource\Pages;
use App\Filament\Resources\BahanResource\RelationManagers;
use App\Models\Bahan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BahanResource extends Resource
{
    protected static ?string $model = Bahan::class;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-bag';

    protected static ?string $navigationGroup = 'Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_bahan')->required(),
                Forms\Components\TextInput::make('sat')->label('Satuan')->required(),
                Forms\Components\TextInput::make('harga_master')->numeric(),
                Forms\Components\Select::make('kategori_id')
                    ->relationship('category', 'nama_kategori', fn (Builder $query) => $query->whereNotIn('nama_kategori', ['Alat Berat', 'Alat Ringan', 'Kendaraan']))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('nama_bahan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_bahan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sat')
                    ->searchable()
                    ->label('Satuan'),
                Tables\Columns\TextColumn::make('harga_master')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.nama_kategori'),
                Tables\Columns\ToggleColumn::make('is_active'),
                Tables\Columns\TextColumn::make('userInp')
                    ->label('User Input')
                    ->formatStateUsing(function ($record) {
                        $userName = $record->userInp ? $record->userInp->name : 'Unknown';
                        $formattedDate = $record->created_at;
                        return $userName . ', ' . $formattedDate;
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('userRev')
                    ->label('User Revisi')
                    ->formatStateUsing(function ($record) {
                        $userName = $record->userRev ? $record->userRev->name : 'Unknown';
                        $formattedDate = $record->tgl_revisi;
                        return $userName . ', ' . $formattedDate;
                    })
                    ->html(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id_revisi'] = auth()->id();
                        $data['tgl_revisi'] = now();

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBahans::route('/'),
        ];
    }
}
