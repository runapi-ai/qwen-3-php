<?php

declare(strict_types=1);

namespace RunApi\Qwen3\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Resources\Pricing;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\Qwen3\Models\CompletedImageTaskResponse;
use RunApi\Qwen3\Qwen3Client;
use RunApi\Qwen3\Resources\EditImage;
use RunApi\Qwen3\Resources\TextToImage;

final class Qwen3ClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new Qwen3Client(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToImage::class, $client->textToImage);
        self::assertInstanceOf(EditImage::class, $client->editImage);
        self::assertInstanceOf(Pricing::class, $client->pricing);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new Qwen3Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToImage->create([
            'model' => 'qwen-3-pro-text-to-image',
            'prompt' => 'A product render',
            'output_resolution' => '1k',
            'aspect_ratio' => '1:1',
            'output_format' => 'png',
            'enable_prompt_expansion' => true,
            'enable_safety_checker' => true,
            'negative_prompt' => 'sample',
            'callback_url' => '',
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/qwen_3/text_to_image', $transport->requests[0]->getUri()->getPath());
        self::assertSame('qwen-3-pro-text-to-image', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","images":[{"url":"https://file.runapi.ai/result"}],"generation_stage":"all_audios_ready","extra_field":"kept"}'),
        ]);
        $client = new Qwen3Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToImage->run([
            'model' => 'qwen-3-pro-text-to-image',
            'prompt' => 'A product render',
            'output_resolution' => '1k',
            'aspect_ratio' => '1:1',
            'output_format' => 'png',
            'enable_prompt_expansion' => true,
            'enable_safety_checker' => true,
            'negative_prompt' => 'sample',
            'seed' => 1,
        ]);

        self::assertInstanceOf(CompletedImageTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->images[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/qwen_3/text_to_image/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new Qwen3Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('images is required');

        $client->textToImage->run([
            'model' => 'qwen-3-pro-text-to-image',
            'prompt' => 'A product render',
            'output_resolution' => '1k',
            'aspect_ratio' => '1:1',
            'output_format' => 'png',
            'enable_prompt_expansion' => true,
            'enable_safety_checker' => true,
            'negative_prompt' => 'sample',
            'seed' => 1,
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new Qwen3Client(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('output_resolution must be one of the allowed values');

        $client->textToImage->create([
        'model' => 'qwen-3-pro-text-to-image',
        'prompt' => 'A product render',
        'aspect_ratio' => '1:1',
        'output_format' => 'png',
        'enable_prompt_expansion' => true,
        'enable_safety_checker' => true,
        'negative_prompt' => 'sample',
        'seed' => 1,
        'output_resolution' => 'not-valid',
        ]);
    }


    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new Qwen3Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->editImage->create([
            'model' => 'qwen-3-edit-image',
            'prompt' => 'A product render',
            'source_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
            'output_resolution' => '1k',
            'aspect_ratio' => '1:1',
            'output_format' => 'png',
            'enable_prompt_expansion' => true,
            'enable_safety_checker' => true,
            'negative_prompt' => 'sample',
            'seed' => 1,
        ]);

        self::assertSame('/api/v1/qwen_3/edit_image', $transport->requests[0]->getUri()->getPath());
    }
}
