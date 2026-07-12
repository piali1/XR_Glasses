<?php

namespace Tests\Feature;

use App\Models\ContentItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentItemsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_items_api_returns_context_sensitive_content(): void
    {
        ContentItem::create([
            'type' => 'training',
            'title' => 'Step 1 training video placeholder',
            'version' => 'v1.0',
            'valid_from' => '2026-01-01',
            'valid_until' => '2026-12-31',
            'area' => 'Compounding / Rezeptur',
            'responsible_role' => 'PTA / Pharmacist',
            'approval_status' => 'approved training template',
            'process' => 'ointment',
            'step_number' => 1,
            'display_context' => 'workflow',
            'content' => 'Training media for the first workflow step.',
            'media_type' => 'video_placeholder',
            'media_title' => 'Preparation setup training video',
            'media_url' => '/training-media/preparation-setup',
        ]);

        $this->getJson('/api/content-items?process=ointment&step_number=1')
            ->assertOk()
            ->assertJsonFragment([
                'type' => 'training',
                'title' => 'Step 1 training video placeholder',
                'version' => 'v1.0',
            ]);
    }
}
