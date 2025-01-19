<?php

namespace App\DTOs\Common\Contracts;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use JsonException;

interface BaseDTOInterface
{
    /**
     * Create a DTO from validated request data.
     *
     * @param Request|FormRequest $request
     * @return static
     */
    public static function fromRequest(Request|FormRequest $request): static;

    /**
     * Create a DTO from an array.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static;

    /**
     * Convert the DTO to an array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Convert the DTO to a JSON string.
     *
     * @return string
     * @throws JsonException
     */
    public function toJson(): string;

    /**
     * Merge additional data into the DTO.
     *
     * @param array $data
     * @return $this
     */
    public function merge(array $data): self;

    /**
     * Fill the DTO with data from an array.
     *
     * @param array $data
     * @return $this
     */
    public function fill(array $data): self;

    /**
     * Check if a specific property exists in the DTO.
     *
     * @param string $property
     * @return bool
     */
    public function has(string $property): bool;

    /**
     * Get the value of a specific property.
     *
     * @param string $property
     * @return mixed
     */
    public function get(string $property): mixed;

    /**
     * Set the value of a specific property.
     *
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function set(string $property, mixed $value): self;

    /**
     * Get only the specified properties from the DTO.
     *
     * @param array $properties
     * @return array
     */
    public function only(array $properties): array;

    /**
     * Get all properties except the specified ones.
     *
     * @param array $properties
     * @return array
     */
    public function except(array $properties): array;

    /**
     * Check if all properties in the DTO are empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Check if any property in the DTO is not empty.
     *
     * @return bool
     */
    public function isNotEmpty(): bool;
}
