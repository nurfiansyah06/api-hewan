<?php
namespace App\Transformers;
use App\Animal;
use League\Fractal\TransformerAbstract;

class AnimalTransformer extends TransformerAbstract
{
    public function transform(Animal $animal)
    {
        return [
            'id'            => $animal->id,
            'nama'          => $animal->nama,
            'jenis_hewan'   => $animal->jenis_hewan,
            'jenis_makanan' => $animal->jenis_makanan,
            'warna_bulu'    => $animal->warna_bulu,
            'berat_badan'   => $animal->berat_badan,
            'jumlah_hewan'  => $animal->jumlah_hewan,
            'is_approval'   => $animal->is_approval,
            'waktu_dibuat'  => $animal->created_at->diffForHumans(),
        ];
    }
}

?>