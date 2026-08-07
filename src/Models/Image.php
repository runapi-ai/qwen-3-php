<?php

declare(strict_types=1);

namespace RunApi\Qwen3\Models;

use RunApi\Core\Models\BaseModel;
use RunApi\Core\Support\Payload;

/** Generated image file metadata. */
readonly class Image extends BaseModel
{
    /**
     * @param string $url URL to the generated image file.
     * @param array<string, mixed> $raw Raw response payload preserved by `toArray()`.
     */
    public function __construct(public string $url, array $raw = [])
    {
        parent::__construct($raw === [] ? ['url' => $url] : $raw);
    }

    /**
     * Hydrate generated image metadata from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(url: Payload::string($raw, 'url'), raw: $raw);
    }
}
