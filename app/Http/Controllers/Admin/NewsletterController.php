<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsletterRequest;
use App\Http\Requests\Admin\UpdateNewsletterRequest;
use App\Models\Newsletter;
use App\Models\NewsletterCategory;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Newsletter::class);

        return view('admin.newsletters.index', [
            'newsletters' => Newsletter::query()->with('category')->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Newsletter::class);

        return view('admin.newsletters.create', [
            'categories' => NewsletterCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreNewsletterRequest $request): RedirectResponse
    {
        $newsletter = DB::transaction(function () use ($request): Newsletter {
            $newsletter = Newsletter::create([
                ...$request->safe()->only(['title', 'category_id']),
                'published_at' => now()->toDateString(),
                'image_path' => '',
                'description' => '',
            ]);

            $this->syncBlocks($newsletter, (array) $request->input('blocks', []), true);

            return $newsletter;
        });

        AuditLogger::log('admin.newsletter.created', $newsletter, [], $request);

        return redirect()->route('admin.newsletters.index')->with('status', 'Newsletter created successfully.');
    }

    public function show(Newsletter $newsletter): View
    {
        $this->authorize('view', $newsletter);

        return view('admin.newsletters.show', ['newsletter' => $newsletter->load(['category', 'blocks'])]);
    }

    public function edit(Newsletter $newsletter): View
    {
        $this->authorize('update', $newsletter);

        return view('admin.newsletters.edit', [
            'newsletter' => $newsletter->load(['category', 'blocks']),
            'categories' => NewsletterCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateNewsletterRequest $request, Newsletter $newsletter): RedirectResponse
    {
        $this->authorize('update', $newsletter);

        DB::transaction(function () use ($request, $newsletter): void {
            $newsletter->fill($request->safe()->only(['title', 'category_id']));
            $this->syncBlocks($newsletter, (array) $request->input('blocks', []), false);
            $newsletter->save();
        });

        AuditLogger::log('admin.newsletter.updated', $newsletter, [], $request);

        return redirect()->route('admin.newsletters.index')->with('status', 'Newsletter updated successfully.');
    }

    public function destroy(Newsletter $newsletter): RedirectResponse
    {
        $this->authorize('delete', $newsletter);

        foreach ($newsletter->blocks()->get() as $block) {
            $this->deleteImage($block->image_path);
        }

        $newsletter->delete();

        AuditLogger::log('admin.newsletter.deleted', $newsletter);

        return redirect()->route('admin.newsletters.index')->with('status', 'Newsletter deleted successfully.');
    }

    /**
     * @param array<int, array<string, mixed>> $blocks
     */
    private function syncBlocks(Newsletter $newsletter, array $blocks, bool $isCreate): void
    {
        $existingPaths = $newsletter->blocks()->where('type', 'image')->pluck('image_path')->filter()->all();
        $retainedPaths = [];
        $payloads = [];

        foreach (array_values($blocks) as $index => $block) {
            $type = $block['type'] ?? null;

            if ($type === 'image') {
                /** @var UploadedFile|null $uploadedImage */
                $uploadedImage = data_get(request()->file('blocks'), "{$index}.image");
                $existingPath = $isCreate ? null : ($block['existing_image_path'] ?? null);
                $imagePath = $uploadedImage ? $this->storeImage($uploadedImage) : $existingPath;

                if ($imagePath) {
                    $retainedPaths[] = $imagePath;
                }

                $payloads[] = [
                    'type' => 'image',
                    'point_title' => null,
                    'point_body' => null,
                    'image_path' => $imagePath,
                    'sort_order' => $index + 1,
                ];

                continue;
            }

            if ($type === 'description') {
                $payloads[] = [
                    'type' => 'description',
                    'point_title' => null,
                    'point_body' => trim((string) ($block['description_body'] ?? '')),
                    'image_path' => null,
                    'sort_order' => $index + 1,
                ];

                continue;
            }

            if (in_array($type, ['h2', 'h3', 'h4'], true)) {
                $payloads[] = [
                    'type' => $type,
                    'point_title' => null,
                    'point_body' => trim((string) ($block['heading_text'] ?? '')),
                    'image_path' => null,
                    'sort_order' => $index + 1,
                ];

                continue;
            }

            $pointInputs = collect((array) ($block['point_inputs'] ?? []))
                ->map(fn ($item): string => trim((string) $item))
                ->filter()
                ->values()
                ->all();

            $payloads[] = [
                'type' => 'point',
                'point_title' => trim((string) ($block['point_title'] ?? '')),
                'point_body' => json_encode($pointInputs, JSON_UNESCAPED_UNICODE),
                'image_path' => null,
                'sort_order' => $index + 1,
            ];
        }

        foreach (array_diff($existingPaths, $retainedPaths) as $removedPath) {
            $this->deleteImage($removedPath);
        }

        $newsletter->image_path = collect($payloads)
            ->first(fn (array $payload): bool => $payload['type'] === 'image' && filled($payload['image_path']))['image_path'] ?? '';

        $newsletter->description = collect($payloads)
            ->first(fn (array $payload): bool => $payload['type'] === 'description' && filled($payload['point_body']))['point_body'] ?? '';

        $newsletter->save();
        $newsletter->blocks()->delete();
        $newsletter->blocks()->createMany($payloads);
    }

    private function storeImage(?UploadedFile $image): string
    {
        if (! $image) {
            abort(422, 'Newsletter image is required.');
        }

        $directory = public_path('uploads/newsletters');
        File::ensureDirectoryExists($directory);

        $filename = Str::uuid()->toString().'.'.$image->getClientOriginalExtension();
        $image->move($directory, $filename);

        return 'uploads/newsletters/'.$filename;
    }

    private function deleteImage(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
