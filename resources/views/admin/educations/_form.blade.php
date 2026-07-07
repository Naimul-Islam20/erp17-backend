<div>
    <label for="category_id">Category</label>
    <select id="category_id" name="category_id" required>
        <option value="">Select a category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $education->category_id ?? null) == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    @error('category_id') <div class="error">{{ $message }}</div> @enderror
</div>

<div style="margin-top: 14px;">
    <label for="title">Title</label>
    <input id="title" name="title" value="{{ old('title', $education->title ?? '') }}" required>
    @error('title') <div class="error">{{ $message }}</div> @enderror
</div>

<div style="margin-top: 14px;">
    <label for="youtube_link">YouTube Link</label>
    <input id="youtube_link" name="youtube_link" type="url" value="{{ old('youtube_link', $education->youtube_link ?? '') }}" placeholder="https://www.youtube.com/watch?v=..." required>
    @error('youtube_link') <div class="error">{{ $message }}</div> @enderror
</div>
