<?php

// app/DTOs/BaseDTO.php
namespace App\DTOs\Common;

use App\DTOs\Common\Contracts\BaseDTOInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use JsonException;

abstract class BaseDTO implements BaseDTOInterface
{
    /**
     * Create a DTO from validated request data.
     *
     * @param Request|FormRequest $request
     * @return static
     */
    public static function fromRequest(Request|FormRequest $request): static
    {
        // Use validated data if it's a FormRequest, otherwise use all data
        $data = $request instanceof FormRequest ? $request->validated() : $request->all();
        return new static(...$data);
    }

    /**
     * Create a DTO from an array.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(...$data);
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });
    }

    /**
     * Convert the DTO to a JSON string.
     *
     * @return string
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * Merge additional data into the DTO.
     *
     * @param array $data
     * @return $this
     */
    public function merge(array $data): self
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        return $this;
    }

    /**
     * Fill the DTO with data from an array.
     *
     * @param array $data
     * @return $this
     */
    public function fill(array $data): self
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        return $this;
    }

    /**
     * Check if a specific property exists in the DTO.
     *
     * @param string $property
     * @return bool
     */
    public function has(string $property): bool
    {
        return property_exists($this, $property);
    }

    /**
     * Get the value of a specific property.
     *
     * @param string $property
     * @return mixed
     */
    public function get(string $property): mixed
    {
        return $this->$property ?? null;
    }

    /**
     * Set the value of a specific property.
     *
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function set(string $property, mixed $value): self
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

    /**
     * Get only the specified properties from the DTO.
     *
     * @param array $properties
     * @return array
     */
    public function only(array $properties): array
    {
        return array_intersect_key($this->toArray(), array_flip($properties));
    }

    /**
     * Get all properties except the specified ones.
     *
     * @param array $properties
     * @return array
     */
    public function except(array $properties): array
    {
        return array_diff_key($this->toArray(), array_flip($properties));
    }

    /**
     * Check if all properties in the DTO are empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty(array_filter($this->toArray()));
    }

    /**
     * Check if any property in the DTO is not empty.
     *
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Casts a given field to a given DTO.
     *
     * @param string $field The name of the field to cast.
     * @param string $dtoClass The target DTO class e.g(BaseDto::class).
     * @return mixed The target DTO instance.
     * @throws Exception If the field does not exist or if the DTO cannot be created.
     */
    public function castFieldToDTO(string $field, string $dtoClass): mixed
    {
        // Check if the field exists in the current DTO
        if (!$this->has($field)) {
            throw new Exception("Field '{$field}' does not exist in the DTO.");
        }

        // Get the value of the field
        $value = $this->get($field);

        // If the value is already an instance of the target DTO, return it
        if ($value instanceof $dtoClass) {
            return $value;
        }

        // Check if the target DTO has a fromArray() method

        return $dtoClass::fromArray($value);
    }
}
