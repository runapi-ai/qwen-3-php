<?php

declare(strict_types=1);

namespace RunApi\Qwen3;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\Qwen3\Resources\EditImage;
use RunApi\Qwen3\Resources\TextToImage;

/**
 * Qwen 3 RunAPI PHP client.
 *
 * The client exposes typed model resources plus the universal `files` and
 * `account` resources.
 */
final class Qwen3Client extends BaseClient
{
    /** Text to image operations for Qwen 3. */
    public readonly TextToImage $textToImage;
    /** Image editing operations for Qwen 3. */
    public readonly EditImage $editImage;

    /** Create a Qwen 3 client with optional API key, base URL, and transport overrides. */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToImage = TextToImage::fromHttp($this->http);
        $this->editImage = EditImage::fromHttp($this->http);
    }
}
