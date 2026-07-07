<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEducationRequest;
use App\Http\Requests\Admin\UpdateEducationRequest;
use App\Models\Education;
use App\Models\NewsletterCategory;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EducationController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Education::class);

        return view('admin.educations.index', [
            'educations' => Education::query()->with('category')->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Education::class);

        return view('admin.educations.create', [
            'education' => new Education(),
            'categories' => NewsletterCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreEducationRequest $request): RedirectResponse
    {
        $education = Education::create($request->safe()->only(['title', 'category_id', 'youtube_link']));

        AuditLogger::log('admin.education.created', $education, [], $request);

        return redirect()->route('admin.educations.index')->with('status', 'Education created successfully.');
    }

    public function edit(Education $education): View
    {
        $this->authorize('update', $education);

        return view('admin.educations.edit', [
            'education' => $education->load('category'),
            'categories' => NewsletterCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateEducationRequest $request, Education $education): RedirectResponse
    {
        $this->authorize('update', $education);

        $education->fill($request->safe()->only(['title', 'category_id', 'youtube_link']));

        if (! $education->isDirty()) {
            return redirect()->route('admin.educations.edit', $education)->with('warning', 'No changes found. Education was not updated.');
        }

        $education->save();

        AuditLogger::log('admin.education.updated', $education, [], $request);

        return redirect()->route('admin.educations.index')->with('status', 'Education updated successfully.');
    }

    public function destroy(Education $education): RedirectResponse
    {
        $this->authorize('delete', $education);

        $education->delete();

        AuditLogger::log('admin.education.deleted', $education);

        return redirect()->route('admin.educations.index')->with('status', 'Education deleted successfully.');
    }
}
