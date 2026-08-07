# Qwen 3 PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/qwen-3)](https://packagist.org/packages/runapi-ai/qwen-3)
[![License](https://img.shields.io/github/license/runapi-ai/qwen-3-php)](https://github.com/runapi-ai/qwen-3-php/blob/main/LICENSE)

The Qwen 3 PHP SDK is the language-specific package for Qwen 3
on RunAPI. Use this package when your application needs Composer installs,
associative-array request bodies, task status lookup, and consistent RunAPI
errors in PHP.

This README is the PHP package guide for the public `qwen-3-php` split
repository. For model details, use https://runapi.ai/models/qwen-3; for API
reference, use https://runapi.ai/docs/api/qwen-3/text-to-image; for SDK docs, use
https://runapi.ai/docs/resources/sdks.

## Install

```bash
composer require runapi-ai/qwen-3
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\Qwen3\Qwen3Client;

$client = new Qwen3Client(); // reads RUNAPI_API_KEY

$editImageTask = $client->editImage->create([
    'model' => 'qwen-3-edit-image',
    'aspect_ratio' => '1:1',
    'enable_prompt_expansion' => true,
    'enable_safety_checker' => true,
    'negative_prompt' => 'sample',
    'output_format' => 'png',
    'output_resolution' => '1k',
    'prompt' => 'Make it golden hour',
    'seed' => 1,
    'source_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
]);

$task = $client->textToImage->create([
    'model' => 'qwen-3-pro-text-to-image',
    'aspect_ratio' => '1:1',
    'enable_prompt_expansion' => true,
    'enable_safety_checker' => true,
    'negative_prompt' => 'sample',
    'output_format' => 'png',
    'output_resolution' => '1k',
    'prompt' => 'A precise product render on white marble',
    'seed' => 1,
]);

$status = $client->textToImage->get($task->id);

$result = $client->textToImage->run([
    'model' => 'qwen-3-pro-text-to-image',
    'aspect_ratio' => '1:1',
    'enable_prompt_expansion' => true,
    'enable_safety_checker' => true,
    'negative_prompt' => 'sample',
    'output_format' => 'png',
    'output_resolution' => '1k',
    'prompt' => 'A serene mountain lake at dawn',
    'seed' => 1,
]);

echo $result->images[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest
task state, and `run()` when a script should create and poll until completion.
In web request handlers, prefer `create()` plus webhook or later `get()`
polling so a worker is not held open.


RunAPI-generated file URLs are temporary. Download and store generated files
in your own durable storage within the retention window; do not treat returned
URLs as long-term assets.

## Language notes

Pass request parameters as associative arrays with snake_case keys. The
available resources are `textToImage`, `editImage`. Keep `RUNAPI_API_KEY` in the environment
or your secret manager; never commit API keys or callback secrets.

## Links

- Model page: https://runapi.ai/models/qwen-3
- SDK docs: https://runapi.ai/docs/resources/sdks
- Product docs: https://runapi.ai/docs/api/qwen-3/text-to-image
- Pricing and rate limits: https://runapi.ai/models/qwen-3/text-to-image
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/qwen-3-php
- Multi-language SDK repository: https://github.com/runapi-ai/qwen-3-sdk

## License

Licensed under the Apache License, Version 2.0.
