# Changelog

## [v0.2.0](https://github.com/runapi-ai/qwen-3-php/releases/tag/v0.2.0) - 2026-09-30

### Changed
- Send request parameters to the service without local validation. Model ids and parameter values the service supports work without an SDK upgrade; static types and enum constants remain for completion.
  Migration: Invalid parameters now throw `ValidationException` built from the service's 400 response, including its status and message, instead of a `ValidationException` thrown locally before the request.


## [v0.1.0](https://github.com/runapi-ai/qwen-3-php/releases/tag/v0.1.0) - 2026-08-07

### Added
- Add typed text-to-image and image editing resources for PHP applications.
