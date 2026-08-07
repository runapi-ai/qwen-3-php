<?php

declare(strict_types=1);

namespace RunApi\Qwen3\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Qwen3\Models\CompletedImageTaskResponse;
use RunApi\Qwen3\Models\ImageTaskResponse;
use RunApi\Qwen3\Types;

/** Image to image operations for Qwen 3. */
readonly class EditImage extends TypedConfiguredResource
{
    /**
     * Create a image to image task and return immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   source_image_urls: list<string>,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   enable_prompt_expansion?: bool,
     *   enable_safety_checker?: bool,
     *   negative_prompt?: string,
     *   output_format?: string,
     *   output_resolution?: string,
     *   seed?: int
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /** Fetch the current status of a image to image task. */
    public function get(string $id, ?RequestOptions $options = null): ImageTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var ImageTaskResponse $response */
        return $response;
    }

    /**
     * Create a image to image task and poll until it completes.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   source_image_urls: list<string>,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   enable_prompt_expansion?: bool,
     *   enable_safety_checker?: bool,
     *   negative_prompt?: string,
     *   output_format?: string,
     *   output_resolution?: string,
     *   seed?: int
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedImageTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedImageTaskResponse $response */
        return $response;
    }

    /** Create the resource using the shared RunAPI HTTP transport. */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/qwen_3/edit_image',
            'qwen-3/edit-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
            Types::EDIT_IMAGE_MODELS,
            'edit-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
        );
    }
}
