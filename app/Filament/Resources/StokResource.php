<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Stok;
use Filament\Tables;
use App\Models\Bahan;
use App\Models\Gudang;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Livewire\Attributes\Reactive;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Filament\Resources\StokResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StokResource\RelationManagers;
use Filament\Forms\Set;

class StokResource extends Resource
{
    protected static ?string $model = Stok::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Warehouse';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lokasi_id')
                    ->relationship('lokasi', 'nama_lokasi')
                    ->required()
                    ->afterStateUpdated(function (Set $set) {
                        $set('gudang_id', null);
                    })
                    ->live(),
                Forms\Components\Select::make('gudang_id')
                    ->options(fn (Get $get): Collection => Gudang::query()
                        ->where('lokasi_id', $get('lokasi_id'))
                        ->pluck('nama_gudang', 'id'))
                    ->live(),
                Forms\Components\Select::make('bahan_id')
                    ->label('Bahan')
                    ->required()
                    ->searchable()
                    ->hidden(fn (callable $get) => !$get('gudang_id') || !$get('lokasi_id'))
                    ->getSearchResultsUsing(function (string $search, callable $get) {
                        $gudangId = $get('gudang_id');
                        $lokasiId = $get('lokasi_id');

                        $results = Bahan::query()
                            ->where(function ($query) use ($search) {
                                $query->where('nama_bahan', 'like', "%{$search}%")
                                    ->orWhere('kode_bahan', 'like', "%{$search}%");
                            })
                            ->whereDoesntHave('stok', function ($query) use ($gudangId, $lokasiId) {
                                $query->where('gudang_id', $gudangId)
                                    ->where('lokasi_id', $lokasiId);
                            })
                            ->get();

                        return $results->mapWithKeys(function ($bahan) {
                            return [$bahan->id => $bahan->nama_bahan . ' (' . $bahan->kode_bahan . ')'];
                        });
                    })
                    ->getOptionLabelUsing(fn ($value): ?string => Bahan::find($value)?->nama_bahan . ' (' . Bahan::find($value)?->kode_bahan . ')')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gudang.nama_gudang'),
                Tables\Columns\TextColumn::make('lokasi.nama_lokasi'),
                Tables\Columns\TextColumn::make('bahan.nama_bahan'),
                Tables\Columns\TextColumn::make('jumlah'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListStoks::route('/'),
            'create' => Pages\CreateStok::route('/create'),
            'edit' => Pages\EditStok::route('/{record}/edit'),
        ];
    }
}
