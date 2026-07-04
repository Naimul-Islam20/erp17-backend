@php
    $initialBlocks = old('blocks');

    if ($initialBlocks === null) {
        $initialBlocks = isset($newsletter)
            ? $newsletter->blocks->map(fn ($block) => [
                'type' => $block->type,
                'point_title' => $block->point_title,
                'description_body' => $block->type === 'description' ? $block->point_body : '',
                'heading_text' => in_array($block->type, ['h2', 'h3', 'h4'], true) ? $block->point_body : '',
                'point_inputs' => $block->type === 'point'
                    ? (is_array(json_decode((string) $block->point_body, true))
                        ? json_decode((string) $block->point_body, true)
                        : [trim((string) $block->point_body)])
                    : [],
                'existing_image_path' => $block->image_path,
            ])->values()->all()
            : [];
    }

    $initialBlocks = is_array($initialBlocks) ? array_values($initialBlocks) : [];

    $initialBlocks = array_map(function ($block) {
        if (($block['type'] ?? null) !== 'point') {
            return $block;
        }

        $pointInputs = $block['point_inputs'] ?? null;

        if (! is_array($pointInputs)) {
            $legacyPointBody = trim((string) ($block['point_body'] ?? ''));
            $pointInputs = $legacyPointBody !== '' ? [$legacyPointBody] : [''];
        }

        $pointInputs = array_values(array_map(
            fn ($item) => is_string($item) ? $item : '',
            $pointInputs
        ));

        if ($pointInputs === []) {
            $pointInputs = [''];
        }

        $block['point_inputs'] = $pointInputs;

        return $block;
    }, $initialBlocks);
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if (! empty($isEdit))
        @method('PUT')
    @endif

    <div>
        <label for="category_id">Category</label>
        <select id="category_id" name="category_id" required>
            <option value="">Select a category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', isset($newsletter) ? $newsletter->category_id : null) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div style="margin-top: 16px;">
        <div>
            <label for="title">Newsletter Title</label>
            <input id="title" name="title" value="{{ old('title', isset($newsletter) ? $newsletter->title : '') }}" required>
            @error('title') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>

    @error('blocks') <div class="error">{{ $message }}</div> @enderror

    <div id="newsletterBlocks" class="builder-list" data-next-index="{{ count($initialBlocks) }}">
        @foreach ($initialBlocks as $index => $block)
            @if (($block['type'] ?? null) === 'image')
                <div class="builder-card" data-block-type="image">
                    <div class="builder-card-header">
                        <div></div>
                        <div class="builder-actions">
                            <button class="btn btn-danger" type="button" data-remove-block>Remove</button>
                        </div>
                    </div>

                    <input name="blocks[{{ $index }}][type]" type="hidden" value="image">
                    <input name="blocks[{{ $index }}][existing_image_path]" type="hidden" value="{{ $block['existing_image_path'] ?? '' }}">

                    <div>
                        <label for="newsletter-block-image-{{ $index }}">Image</label>
                        <input id="newsletter-block-image-{{ $index }}" name="blocks[{{ $index }}][image]" type="file" accept="image/*">
                        <small class="helper-text">Select an image for this section.</small>
                        @error('blocks.'.$index.'.image') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    @if (! empty($block['existing_image_path']))
                        <div class="block-preview">
                            <img src="{{ asset($block['existing_image_path']) }}" alt="Newsletter image preview">
                        </div>
                    @endif
                </div>
            @elseif (($block['type'] ?? null) === 'point')
                <div class="builder-card" data-block-type="point" data-block-index="{{ $index }}" data-next-point-index="{{ count($block['point_inputs'] ?? []) }}">
                    <div class="builder-card-header">
                        <div></div>
                        <div class="builder-actions">
                            <button class="btn btn-danger" type="button" data-remove-block>Remove</button>
                        </div>
                    </div>

                    <input name="blocks[{{ $index }}][type]" type="hidden" value="point">

                    <div>
                        <label for="newsletter-block-point-title-{{ $index }}">Point Title</label>
                        <input id="newsletter-block-point-title-{{ $index }}" name="blocks[{{ $index }}][point_title]" value="{{ $block['point_title'] ?? '' }}">
                        @error('blocks.'.$index.'.point_title') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div style="margin-top: 14px;">
                        <label>Point Input</label>
                        <div data-point-inputs>
                            @foreach (($block['point_inputs'] ?? ['']) as $pointInputIndex => $pointInput)
                                <div data-point-input-item @if ($pointInputIndex > 0) style="margin-top: 10px;" @endif>
                                    <div class="point-input-row">
                                        <textarea
                                            id="newsletter-block-point-body-{{ $index }}-{{ $pointInputIndex }}"
                                            name="blocks[{{ $index }}][point_inputs][{{ $pointInputIndex }}]"
                                            rows="1">{{ $pointInput }}</textarea>
                                        <button class="icon-delete-btn" type="button" data-remove-point-input aria-label="Remove point input" title="Remove point input">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M3 6h18"></path>
                                                <path d="M8 6V4h8v2"></path>
                                                <path d="M19 6l-1 14H6L5 6"></path>
                                                <path d="M10 11v6"></path>
                                                <path d="M14 11v6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('blocks.'.$index.'.point_inputs.'.$pointInputIndex) <div class="error">{{ $message }}</div> @enderror

                                    <div class="point-input-addrow">
                                        <button class="icon-add-btn" type="button" data-add-point-after>
                                            <span class="icon-add-symbol">+</span>
                                            <span>Add Another Point</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('blocks.'.$index.'.point_inputs') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>
            @elseif (($block['type'] ?? null) === 'description')
                <div class="builder-card" data-block-type="description">
                    <div class="builder-card-header">
                        <div></div>
                        <div class="builder-actions">
                            <button class="btn btn-danger" type="button" data-remove-block>Remove</button>
                        </div>
                    </div>

                    <input name="blocks[{{ $index }}][type]" type="hidden" value="description">

                    <div>
                        <label for="newsletter-block-description-{{ $index }}">Description</label>
                        <textarea id="newsletter-block-description-{{ $index }}" name="blocks[{{ $index }}][description_body]" rows="4">{{ $block['description_body'] ?? '' }}</textarea>
                        @error('blocks.'.$index.'.description_body') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>
            @else
                <div class="builder-card" data-block-type="{{ $block['type'] }}">
                    <div class="builder-card-header">
                        <div></div>
                        <div class="builder-actions">
                            <button class="btn btn-danger" type="button" data-remove-block>Remove</button>
                        </div>
                    </div>

                    <input name="blocks[{{ $index }}][type]" type="hidden" value="{{ $block['type'] }}">

                    <div>
                        <label for="newsletter-block-heading-{{ $block['type'] }}-{{ $index }}">{{ strtoupper($block['type']) }} Text</label>
                        <input id="newsletter-block-heading-{{ $block['type'] }}-{{ $index }}" name="blocks[{{ $index }}][heading_text]" value="{{ $block['heading_text'] ?? '' }}">
                        @error('blocks.'.$index.'.heading_text') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="builder-toolbar">
        <button class="btn btn-outline" type="button" data-add-block="image">+ Add Image</button>
        <button class="btn btn-soft" type="button" data-add-block="point">+ Add Point</button>
        <button class="btn btn-muted" type="button" data-add-block="description">+ Description</button>
        <button class="btn btn-outline" type="button" data-add-block="h2">H2</button>
        <button class="btn btn-outline" type="button" data-add-block="h3">H3</button>
        <button class="btn btn-outline" type="button" data-add-block="h4">H4</button>
    </div>

    <div style="margin-top: 16px;">
        <button class="btn btn-primary" type="submit" onclick="return confirm('{{ $submitConfirm }}')">{{ $submitLabel }}</button>
    </div>
</form>

<script>
    (() => {
        const container = document.getElementById('newsletterBlocks');

        if (!container) return;

        const getNextIndex = () => {
            const current = Number(container.dataset.nextIndex || '0');
            container.dataset.nextIndex = String(current + 1);

            return current;
        };

        const imageBlockMarkup = (index) => `
            <div class="builder-card" data-block-type="image">
                <div class="builder-card-header">
                    <div></div>
                    <div class="builder-actions">
                        <button class="btn btn-danger" type="button" data-remove-block>Remove</button>
                    </div>
                </div>
                <input name="blocks[${index}][type]" type="hidden" value="image">
                <input name="blocks[${index}][existing_image_path]" type="hidden" value="">
                <div>
                    <label for="newsletter-block-image-${index}">Image</label>
                    <input id="newsletter-block-image-${index}" name="blocks[${index}][image]" type="file" accept="image/*">
                    <small class="helper-text">Select an image for this section.</small>
                </div>
            </div>
        `;

        const pointInputMarkup = (blockIndex, pointInputIndex) => `
            <div data-point-input-item style="margin-top: ${pointInputIndex === 0 ? '0' : '10px'};">
                <div class="point-input-row">
                    <textarea id="newsletter-block-point-body-${blockIndex}-${pointInputIndex}" name="blocks[${blockIndex}][point_inputs][${pointInputIndex}]" rows="1"></textarea>
                    <button class="icon-delete-btn" type="button" data-remove-point-input aria-label="Remove point input" title="Remove point input">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 6h18"></path>
                            <path d="M8 6V4h8v2"></path>
                            <path d="M19 6l-1 14H6L5 6"></path>
                            <path d="M10 11v6"></path>
                            <path d="M14 11v6"></path>
                        </svg>
                    </button>
                </div>
                <div class="point-input-addrow">
                    <button class="icon-add-btn" type="button" data-add-point-after>
                        <span class="icon-add-symbol">+</span>
                        <span>Add Another Point</span>
                    </button>
                </div>
            </div>
        `;

        const pointBlockMarkup = (index) => `
            <div class="builder-card" data-block-type="point" data-block-index="${index}" data-next-point-index="1">
                <div class="builder-card-header">
                    <div></div>
                    <div class="builder-actions">
                        <button class="btn btn-danger" type="button" data-remove-block>Remove</button>
                    </div>
                </div>
                <input name="blocks[${index}][type]" type="hidden" value="point">
                <div>
                    <label for="newsletter-block-point-title-${index}">Point Title</label>
                    <input id="newsletter-block-point-title-${index}" name="blocks[${index}][point_title]">
                </div>
                <div style="margin-top: 14px;">
                    <label>Point Input</label>
                    <div data-point-inputs>
                        ${pointInputMarkup(index, 0)}
                    </div>
                </div>
            </div>
        `;

        const descriptionBlockMarkup = (index) => `
            <div class="builder-card" data-block-type="description">
                <div class="builder-card-header">
                    <div></div>
                    <div class="builder-actions">
                        <button class="btn btn-danger" type="button" data-remove-block>Remove</button>
                    </div>
                </div>
                <input name="blocks[${index}][type]" type="hidden" value="description">
                <div>
                    <label for="newsletter-block-description-${index}">Description</label>
                    <textarea id="newsletter-block-description-${index}" name="blocks[${index}][description_body]" rows="4"></textarea>
                </div>
            </div>
        `;

        const headingBlockMarkup = (index, type) => `
            <div class="builder-card" data-block-type="${type}">
                <div class="builder-card-header">
                    <div></div>
                    <div class="builder-actions">
                        <button class="btn btn-danger" type="button" data-remove-block>Remove</button>
                    </div>
                </div>
                <input name="blocks[${index}][type]" type="hidden" value="${type}">
                <div>
                    <label for="newsletter-block-heading-${type}-${index}">${type.toUpperCase()} Text</label>
                    <input id="newsletter-block-heading-${type}-${index}" name="blocks[${index}][heading_text]">
                </div>
            </div>
        `;

        const createBlock = (type) => {
            const index = getNextIndex();

            if (type === 'image') return imageBlockMarkup(index);
            if (type === 'description') return descriptionBlockMarkup(index);
            if (['h2', 'h3', 'h4'].includes(type)) return headingBlockMarkup(index, type);

            return pointBlockMarkup(index);
        };

        const refreshPointInputButtons = (block) => {
            if (!block) return;

            const items = Array.from(block.querySelectorAll('[data-point-input-item]'));

            items.forEach((item, index) => {
                const addButton = item.querySelector('[data-add-point-after]');

                if (!addButton) return;

                addButton.style.display = index === items.length - 1 ? 'inline-flex' : 'none';
            });
        };

        const refreshAllPointBlocks = () => {
            container.querySelectorAll('[data-block-type="point"]').forEach((block) => {
                refreshPointInputButtons(block);
            });
        };

        document.querySelectorAll('[data-add-block]').forEach((button) => {
            button.addEventListener('click', () => {
                container.insertAdjacentHTML('beforeend', createBlock(button.dataset.addBlock));
                refreshAllPointBlocks();
            });
        });

        container.addEventListener('click', (event) => {
            const removeButton = event.target.closest('[data-remove-block]');

            if (removeButton) {
                removeButton.closest('.builder-card')?.remove();
                return;
            }

            const addPointButton = event.target.closest('[data-add-point-after]');

            if (addPointButton) {
                const currentBlock = addPointButton.closest('.builder-card');
                if (!currentBlock) return;

                const blockIndex = Number(currentBlock.dataset.blockIndex || '-1');
                const nextPointIndex = Number(currentBlock.dataset.nextPointIndex || '0');
                const pointInputs = currentBlock.querySelector('[data-point-inputs]');

                if (blockIndex < 0 || !pointInputs) return;

                pointInputs.insertAdjacentHTML('beforeend', pointInputMarkup(blockIndex, nextPointIndex));
                currentBlock.dataset.nextPointIndex = String(nextPointIndex + 1);
                refreshPointInputButtons(currentBlock);
                return;
            }

            const removePointInputButton = event.target.closest('[data-remove-point-input]');

            if (removePointInputButton) {
                const currentBlock = removePointInputButton.closest('.builder-card');
                const pointItem = removePointInputButton.closest('[data-point-input-item]');

                pointItem?.remove();

                if (currentBlock) {
                    const pointInputs = currentBlock.querySelector('[data-point-inputs]');
                    const existingItems = currentBlock.querySelectorAll('[data-point-input-item]');

                    if (pointInputs && existingItems.length === 0) {
                        const blockIndex = Number(currentBlock.dataset.blockIndex || '-1');
                        const nextPointIndex = Number(currentBlock.dataset.nextPointIndex || '0');

                        if (blockIndex >= 0) {
                            pointInputs.insertAdjacentHTML('beforeend', pointInputMarkup(blockIndex, nextPointIndex));
                            currentBlock.dataset.nextPointIndex = String(nextPointIndex + 1);
                        }
                    }

                    refreshPointInputButtons(currentBlock);
                }
            }
        });

        refreshAllPointBlocks();
    })();
</script>
