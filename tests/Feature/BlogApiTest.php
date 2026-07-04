<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogBlock;
use App\Models\NewsletterCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_returns_public_blog_list(): void
    {
        $category = NewsletterCategory::create([
            'name' => 'ERP',
        ]);

        $blog = Blog::create([
            'title' => 'How ERP17 Improves Operations',
            'category_id' => $category->id,
        ]);

        BlogBlock::create([
            'blog_id' => $blog->id,
            'type' => 'image',
            'image_path' => 'uploads/blogs/sample.jpg',
            'sort_order' => 1,
        ]);

        BlogBlock::create([
            'blog_id' => $blog->id,
            'type' => 'description',
            'point_body' => 'This is the first blog description.',
            'sort_order' => 2,
        ]);

        $response = $this->getJson('/api/blogs');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.id', $blog->id)
            ->assertJsonPath('data.0.title', 'How ERP17 Improves Operations')
            ->assertJsonPath('data.0.category', 'ERP')
            ->assertJsonPath('data.0.excerpt', 'This is the first blog description.')
            ->assertJsonPath('data.0.blocks_count', 2)
            ->assertJsonPath('meta.current_page', 1);
    }

    public function test_blog_show_returns_full_block_payload(): void
    {
        $category = NewsletterCategory::create([
            'name' => 'Business',
        ]);

        $blog = Blog::create([
            'title' => 'ERP17 Blog Details',
            'category_id' => $category->id,
        ]);

        BlogBlock::create([
            'blog_id' => $blog->id,
            'type' => 'h2',
            'point_body' => 'Main Heading',
            'sort_order' => 1,
        ]);

        BlogBlock::create([
            'blog_id' => $blog->id,
            'type' => 'point',
            'point_title' => 'Benefits',
            'point_body' => json_encode(['Fast setup', 'Cleaner workflow'], JSON_UNESCAPED_UNICODE),
            'sort_order' => 2,
        ]);

        BlogBlock::create([
            'blog_id' => $blog->id,
            'type' => 'description',
            'point_body' => 'Detailed overview of the product.',
            'sort_order' => 3,
        ]);

        $response = $this->getJson("/api/blogs/{$blog->id}");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $blog->id)
            ->assertJsonPath('data.title', 'ERP17 Blog Details')
            ->assertJsonPath('data.category', 'Business')
            ->assertJsonPath('data.blocks_count', 3)
            ->assertJsonPath('data.blocks.0.type', 'h2')
            ->assertJsonPath('data.blocks.0.text', 'Main Heading')
            ->assertJsonPath('data.blocks.1.type', 'point')
            ->assertJsonPath('data.blocks.1.point_title', 'Benefits')
            ->assertJsonPath('data.blocks.1.points.0', 'Fast setup')
            ->assertJsonPath('data.blocks.1.points.1', 'Cleaner workflow')
            ->assertJsonPath('data.blocks.2.type', 'description')
            ->assertJsonPath('data.blocks.2.body', 'Detailed overview of the product.');
    }
}
