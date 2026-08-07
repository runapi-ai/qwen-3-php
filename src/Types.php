<?php

declare(strict_types=1);

namespace RunApi\Qwen3;

final class Types
{
    /**
     * Allowed model slugs for text to image requests.
     *
     * @var list<string>
     */
    public const TEXT_TO_IMAGE_MODELS = ['qwen-3-pro-text-to-image', 'qwen-3-text-to-image'];

    /**
     * Allowed model slugs for image to image requests.
     *
     * @var list<string>
     */
    public const EDIT_IMAGE_MODELS = ['qwen-3-edit-image', 'qwen-3-pro-edit-image'];

    private function __construct()
    {
    }
}
