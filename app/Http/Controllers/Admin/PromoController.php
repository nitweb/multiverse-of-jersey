<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PromoRequest;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PromoController extends Controller
{
    private const WIDTH = 1200;

    private const HEIGHT = 480;

    private const DIRECTORY = 'uploads/promos';

    public function edit(): View
    {
        $promos = collect(Promo::SLOTS)->map(fn (string $label, int $slot) => Promo::firstOrCreate(['slot' => $slot]));

        return view('admin.promos.edit', compact('promos'));
    }

    public function update(PromoRequest $request): RedirectResponse
    {
        foreach (array_keys(Promo::SLOTS) as $slot) {
            $promo = Promo::firstOrCreate(['slot' => $slot]);
            $data = ['link' => $request->input("promos.{$slot}.link")];

            if ($request->hasFile("promos.{$slot}.image")) {
                if ($promo->image) {
                    File::delete(public_path($promo->image));
                }
                $data['image'] = $this->storeImage($request->file("promos.{$slot}.image"));
            }

            $promo->update($data);
        }

        return to_route('admin.promos.edit')->with('success', 'Promos updated');
    }

    private function storeImage(UploadedFile $file): string
    {
        File::ensureDirectoryExists(public_path(self::DIRECTORY));

        $path = self::DIRECTORY.'/'.Str::uuid().'.webp';

        (new ImageManager(new Driver))
            ->read($file)
            ->cover(self::WIDTH, self::HEIGHT)
            ->toWebp(85)
            ->save(public_path($path));

        return $path;
    }
}
