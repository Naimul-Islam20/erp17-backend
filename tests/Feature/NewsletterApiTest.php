<?php

namespace Tests\Feature;

use App\Models\Newsletter;
use App\Models\NewsletterBlock;
use App\Models\NewsletterCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_newsletter_index_returns_summary_fields(): void
    {
        $category = NewsletterCategory::create([
            'name' => 'Updates',
        ]);

        $newsletter = Newsletter::create([
            'title' => 'ERP17 Product Update',
            'category_id' => $category->id,
            'published_at' => '2026-07-04',
            'image_path' => 'uploads/newsletters/sample.jpg',
            'description' => 'Latest product improvements.',
        ]);

        NewsletterBlock::create([
            'newsletter_id' => $newsletter->id,
            'type' => 'image',
            'image_path' => 'uploads/newsletters/sample.jpg',
            'sort_order' => 1,
        ]);

        NewsletterBlock::create([
            'newsletter_id' => $newsletter->id,
            'type' => 'description',
            'point_body' => 'Latest product improvements.',
            'sort_order' => 2,
        ]);

        $response = $this->getJson('/api/newsletters');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.id', $newsletter->id)
            ->assertJsonPath('data.0.title', 'ERP17 Product Update')
            ->assertJsonPath('data.0.category', 'Updates')
            ->assertJsonPath('data.0.description', 'Latest product improvements.');
    }

    public function test_newsletter_show_returns_full_block_payload(): void
    {
        $category = NewsletterCategory::create([
            'name' => 'Announcements',
        ]);

        $newsletter = Newsletter::create([
            'title' => 'ERP17 New Features',
            'category_id' => $category->id,
            'published_at' => '2026-07-04',
            'image_path' => 'uploads/newsletters/cover.jpg',
            'description' => 'Feature overview',
        ]);

        NewsletterBlock::create([
            'newsletter_id' => $newsletter->id,
            'type' => 'h2',
            'point_body' => 'Big Update',
            'sort_order' => 1,
        ]);

        NewsletterBlock::create([
            'newsletter_id' => $newsletter->id,
            'type' => 'point',
            'point_title' => 'Highlights',
            'point_body' => json_encode(['Fast', 'Simple'], JSON_UNESCAPED_UNICODE),
            'sort_order' => 2,
        ]);

        NewsletterBlock::create([
            'newsletter_id' => $newsletter->id,
            'type' => 'description',
            'point_body' => 'Detailed announcement text.',
            'sort_order' => 3,
        ]);

        $response = $this->getJson("/api/newsletters/{$newsletter->id}");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $newsletter->id)
            ->assertJsonPath('data.title', 'ERP17 New Features')
            ->assertJsonPath('data.category', 'Announcements')
            ->assertJsonPath('data.blocks.0.type', 'h2')
            ->assertJsonPath('data.blocks.0.text', 'Big Update')
            ->assertJsonPath('data.blocks.1.type', 'point')
            ->assertJsonPath('data.blocks.1.point_title', 'Highlights')
            ->assertJsonPath('data.blocks.1.points.0', 'Fast')
            ->assertJsonPath('data.blocks.1.points.1', 'Simple')
            ->assertJsonPath('data.blocks.2.type', 'description')
            ->assertJsonPath('data.blocks.2.body', 'Detailed announcement text.');
    }
}
