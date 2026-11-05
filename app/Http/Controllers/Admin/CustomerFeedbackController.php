<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerFeedbackRequest;
use App\Models\CustomerFeedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CustomerFeedbackController extends Controller
{
    private const WIDTH = 800;

    private const HEIGHT = 1000;

    private const DIRECTORY = 'uploads/customer-feedbacks';

    public function index(): View
    {
        $feedbacks = CustomerFeedback::latest('id')->get();

        return view('admin.customer-feedbacks.index', compact('feedbacks'));
    }

    public function create(): View
    {
        return view('admin.customer-feedbacks.create');
    }

    public function store(CustomerFeedbackRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['image'] = $this->storeImage($request->file('image'));

        CustomerFeedback::create($data);

        return to_route('admin.customer-feedbacks.index')->with('success', 'Feedback photo saved');
    }

    public function edit(CustomerFeedback $customerFeedback): View
    {
        return view('admin.customer-feedbacks.edit', ['feedback' => $customerFeedback]);
    }

    public function update(CustomerFeedbackRequest $request, CustomerFeedback $customerFeedback): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            File::delete(public_path($customerFeedback->image));
            $data['image'] = $this->storeImage($request->file('image'));
        }

        $customerFeedback->update($data);

        return to_route('admin.customer-feedbacks.edit', $customerFeedback)->with('success', 'Feedback photo updated');
    }

    public function destroy(CustomerFeedback $customerFeedback): RedirectResponse
    {
        File::delete(public_path($customerFeedback->image));
        $customerFeedback->delete();

        return to_route('admin.customer-feedbacks.index')->with('success', 'Feedback photo deleted');
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
