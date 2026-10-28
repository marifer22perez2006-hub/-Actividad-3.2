<?php

namespace Tests\Feature;

use App\Services\ImageUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImageUploadServiceTest extends TestCase
{
    public function test_it_uploads_images_to_freeimage_with_the_configured_key(): void
    {
        config()->set('services.freeimage.key', 'test-api-key');

        Http::fake([
            'https://freeimage.host/api/1/upload' => Http::response([
                'image' => ['url' => 'https://freeimage.host/images/product-image.jpg'],
            ]),
        ]);

        $url = ImageUploadService::upload(
            UploadedFile::fake()->create('photo.jpg', 10, 'image/jpeg'),
            'producto-27-imagen1'
        );

        $this->assertSame('https://freeimage.host/images/product-image.jpg', $url);
        Http::assertSent(function ($request) {
            return $request->url() === 'https://freeimage.host/api/1/upload'
                && $request->method() === 'POST'
                && $request->isMultipart()
                && str_contains($request->body(), 'test-api-key')
                && str_contains($request->body(), 'producto-27-imagen1.jpg');
        });
        Http::assertSentCount(1);
    }
}
