<?php

declare(strict_types=1);

namespace RunApi\Qwen3\Models;

use RunApi\Core\Models\TaskResponse;
use RunApi\Core\Support\Payload;

/** Async image task response with lifecycle status and output images. */
readonly class ImageTaskResponse extends TaskResponse
{
    /**
     * @param list<Image> $images Generated image files when the task has completed.

     * @param array<string, mixed> $raw Raw response payload preserved by `toArray()`.
     */
    public function __construct(?string $id, string $status, ?string $error = null, public array $images = [], array $raw = [])
    {
        parent::__construct(id: $id, status: $status, error: $error, raw: $raw === [] ? ['id' => $id, 'status' => $status, 'error' => $error, 'images' => array_map(static fn (Image $image): array => $image->toArray(), $images)] : $raw);
    }

    /**
     * Hydrate a task status response from a RunAPI response object.
     *
     * @param array<string, mixed> $raw
     */
    public static function fromArray(array $raw): self
    {
        return new self(id: Payload::string($raw, 'id'), status: Payload::string($raw, 'status'), error: self::error($raw), images: self::images($raw), raw: $raw);
    }

    /**
     * @param array<string, mixed> $raw
     *
     * @return list<Image>
     */
    protected static function images(array $raw, bool $required = false): array
    {
        return Payload::listOf($raw, 'images', Image::fromArray(...), $required);
    }


}
