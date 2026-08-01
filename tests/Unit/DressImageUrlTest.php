<?php

namespace Tests\Unit;

use App\Models\Dress;
use Tests\TestCase;

class DressImageUrlTest extends TestCase
{
    public function test_main_image_url_falls_back_to_placeholder_when_no_image_exists(): void
    {
        $dress = new Dress();

        $this->assertSame(asset('logo.webp'), $dress->main_image_url);
    }
}
