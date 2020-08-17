<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Animal;
use App\Transformers\AnimalTransformer;
use Auth;

class AnimalController extends Controller
{
    public function getDatabyApprove()
    {
       $animal = Animal::where('is_approval','DISETUJUI')->get();
       
       $response = fractal()
            ->collection($animal)
            ->transformWith(new AnimalTransformer)
            ->toArray();

        return ResponseFormatter::success($response,'Data berhasil diambil');
    }

    public function add(Request $request, Animal $animal)
    {
        $this->validate($request, [
            'nama'          => 'required|string',
            'jenis_hewan'   => 'required',
            'jenis_makanan' => 'required|in:Herbivora,Omnivora,Karnivora',
            'warna_bulu'    => 'required',
            'berat_badan'   => 'required',
            'jumlah_hewan'  => 'required',
            'is_approval'   => 'in:DISETUJUI,DITOLAK,MENUNGGU'
        ]);

        $animal = $animal->create([
            'user_id'       => Auth::user()->id,
            'nama'          => $request->nama,
            'jenis_hewan'   => $request->jenis_hewan,
            'jenis_makanan' => $request->jenis_makanan,
            'berat_badan'   => $request->berat_badan,
            'warna_bulu'    => $request->warna_bulu,
            'jumlah_hewan'  => $request->jumlah_hewan,
            'is_approval'   => 'MENUNGGU'
        ]);

        $response = fractal()
            ->item($animal)
            ->transformWith(new AnimalTransformer)
            ->toArray();

        return ResponseFormatter::success($response,'Data berhasil dimasukkan');

    }

    public function updateAnimalByPetugas(Request $request, Animal $animal, $id)
    {
        $animal = Animal::where('user_id',Auth::user()->id)->first();

        if ($animal->is_approval === 'DITOLAK') {

            return ResponseFormatter::error(404, 'Data sudah ditolak tidak bisa diupdate');

        } else {
            $animal->nama = $request->nama;
            $animal->jenis_hewan = $request->jenis_hewan;
            $animal->jenis_makanan = $request->jenis_makanan;
            $animal->warna_bulu = $request->warna_bulu;
            $animal->berat_badan = $request->berat_badan;
            $animal->jumlah_hewan = $request->jumlah_hewan;
            $animal->save();

             $response = fractal()
                ->item($animal)
                ->transformWith(new AnimalTransformer)
                ->toArray();
            return ResponseFormatter::success($response, 'Data berhasil diupdate');
        }        
    }

    public function deleteAnimal(Request $request, $id)
    {
        $animal = Animal::destroy($id);

        if ($animal)
            return ResponseFormatter::success($animal,'Data berhasil dihapus');
        else
            return ResponseFormatter::error(null, 'Data tidak ada','404');
    }

    public function setApproval(Request $request, $id)
    {
        $request->validate([
            'is_approval' => 'required|in:MENUNGGU,DISETUJUI,DITOLAK'
        ]);

        $item = Animal::find($id);
        $item->is_approval = $request->is_approval;

        $item->save();

        if ($item)
            return ResponseFormatter::success($item,'Data berhasil approval diganti');
        else
            return ResponseFormatter::error(null, 'Data tidak ada','404');

    }

}
