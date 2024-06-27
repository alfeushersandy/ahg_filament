<?php

namespace App\Filament\Resources\BahanResource\Pages;

use App\Models\Bahan;
use Filament\Actions;
use App\Filament\Resources\BahanResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBahans extends ManageRecords
{
    protected static string $resource = BahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id_input'] = auth()->id();
                    //make kode_bahan
                    $produk = Bahan::orderBy('kode_bahan', 'DESC')->latest()->first() ?? new Bahan();
                    $kode = substr($produk->kode_bahan, 1);
                    $data['kode_bahan'] = 'P' . tambah_nol_didepan((int)$kode + 1, 6);
                    $data['tgl_input'] = now();

                    return $data;
                }),
        ];
    }
}
