<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\JsonResponse;

class EducationController extends Controller
{
    public function index(): JsonResponse
    {
        $educations = Education::query()
            ->with('category')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $educations->getCollection()->map(fn (Education $education): array => $this->transform($education)),
            'meta' => [
                'current_page' => $educations->currentPage(),
                'last_page' => $educations->lastPage(),
                'per_page' => $educations->perPage(),
                'total' => $educations->total(),
            ],
        ]);
    }

    public function show(Education $education): JsonResponse
    {
        $education->load('category');

        return response()->json([
            'success' => true,
            'data' => $this->transform($education),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function transform(Education $education): array
    {
        return [
            'id' => $education->id,
            'title' => $education->title,
            'category' => $education->category?->name,
            'youtube_link' => $education->youtube_link,
            'created_at' => $education->created_at,
            'updated_at' => $education->updated_at,
        ];
    }
}
