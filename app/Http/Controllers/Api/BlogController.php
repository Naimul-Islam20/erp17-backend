<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogBlock;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    public function index(): JsonResponse
    {
        $blogs = Blog::query()
            ->with(['category', 'blocks'])
            ->withCount('blocks')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $blogs->getCollection()->map(function (Blog $blog): array {
                $firstImage = $blog->blocks->firstWhere('type', 'image');
                $firstDescription = $blog->blocks->firstWhere('type', 'description');

                return [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'category' => $blog->category?->name,
                    'featured_image_url' => $firstImage?->image_path ? asset($firstImage->image_path) : null,
                    'excerpt' => $firstDescription?->point_body,
                    'blocks_count' => $blog->blocks_count,
                    'created_at' => $blog->created_at,
                    'updated_at' => $blog->updated_at,
                ];
            }),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
            ],
        ]);
    }

    public function show(Blog $blog): JsonResponse
    {
        $blog->load(['category', 'blocks']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $blog->id,
                'title' => $blog->title,
                'category' => $blog->category?->name,
                'blocks_count' => $blog->blocks->count(),
                'blocks' => $blog->blocks->map(fn (BlogBlock $block): array => $this->transformBlock($block))->values(),
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at,
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function transformBlock(BlogBlock $block): array
    {
        if ($block->type === 'image') {
            return [
                'id' => $block->id,
                'type' => 'image',
                'image_url' => $block->image_path ? asset($block->image_path) : null,
                'sort_order' => $block->sort_order,
            ];
        }

        if ($block->type === 'point') {
            $points = json_decode((string) $block->point_body, true);

            if (! is_array($points)) {
                $points = filled($block->point_body) ? [trim((string) $block->point_body)] : [];
            }

            return [
                'id' => $block->id,
                'type' => 'point',
                'point_title' => $block->point_title,
                'points' => array_values(array_filter(array_map(
                    fn ($item): string => trim((string) $item),
                    $points
                ))),
                'sort_order' => $block->sort_order,
            ];
        }

        if ($block->type === 'description') {
            return [
                'id' => $block->id,
                'type' => 'description',
                'body' => $block->point_body,
                'sort_order' => $block->sort_order,
            ];
        }

        return [
            'id' => $block->id,
            'type' => $block->type,
            'text' => $block->point_body,
            'sort_order' => $block->sort_order,
        ];
    }
}
