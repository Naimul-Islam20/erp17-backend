<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\NewsletterCategory;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Blog::class);

        return view('admin.blogs.index', [
            'blogs' => Blog::query()->with('category')->withCount('blocks')->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Blog::class);

        return view('admin.blogs.create', [
            'categories' => NewsletterCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreBlogRequest $request): RedirectResponse
    {
        $blog = DB::transaction(function () use ($request): Blog {
            $blog = Blog::create($request->safe()->only(['title', 'category_id']));
            $this->syncBlocks($blog, (array) $request->input('blocks', []), true);

            return $blog;
        });

        AuditLogger::log('admin.blog.created', $blog, [], $request);

        return redirect()->route('admin.blogs.index')->with('status', 'Blog created successfully.');
    }

    public function show(Blog $blog): View
    {
        $this->authorize('view', $blog);

        return view('admin.blogs.show', [
            'blog' => $blog->load(['blocks', 'category']),
        ]);
    }

    public function edit(Blog $blog): View
    {
        $this->authorize('update', $blog);

        return view('admin.blogs.edit', [
            'blog' => $blog->load(['blocks', 'category']),
            'categories' => NewsletterCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateBlogRequest $request, Blog $blog): RedirectResponse
    {
        $this->authorize('update', $blog);

        DB::transaction(function () use ($request, $blog): void {
            $blog->fill($request->safe()->only(['title', 'category_id']));
            $this->syncBlocks($blog, (array) $request->input('blocks', []), false);
            $blog->save();
        });

        AuditLogger::log('admin.blog.updated', $blog->fresh('blocks'), [], $request);

        return redirect()->route('admin.blogs.index')->with('status', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $this->authorize('delete', $blog);

        $blocks = $blog->blocks()->get();

        foreach ($blocks as $block) {
            $this->deleteImage($block->image_path);
        }

        $blog->delete();

        AuditLogger::log('admin.blog.deleted', $blog);

        return redirect()->route('admin.blogs.index')->with('status', 'Blog deleted successfully.');
    }

    /**
     * @param array<int, array<string, mixed>> $blocks
     */
    private function syncBlocks(Blog $blog, array $blocks, bool $isCreate): void
    {
        $existingPaths = $blog->blocks()->where('type', 'image')->pluck('image_path')->filter()->all();
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

        $blog->blocks()->delete();
        $blog->blocks()->createMany($payloads);
    }

    private function storeImage(UploadedFile $image): string
    {
        $directory = public_path('uploads/blogs');
        File::ensureDirectoryExists($directory);

        $filename = Str::uuid()->toString().'.'.$image->getClientOriginalExtension();
        $image->move($directory, $filename);

        return 'uploads/blogs/'.$filename;
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
