<?php

declare(strict_types=1);

namespace TriGym\Core;

final class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    /** @param array<string, mixed> $data */
    public function __construct(private readonly array $data)
    {
    }

    public function required(string $field, string $label): self
    {
        $value = $this->data[$field] ?? '';
        if (!is_string($value) || trim($value) === '') {
            $this->addError($field, "$label wajib diisi.");
        }
        return $this;
    }

    public function email(string $field, string $label = 'Email'): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "$label tidak valid.");
        }
        return $this;
    }

    public function minLength(string $field, int $min, string $label): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if ($value !== '' && mb_strlen($value) < $min) {
            $this->addError($field, "$label minimal $min karakter.");
        }
        return $this;
    }

    public function accepted(string $field, string $message): self
    {
        if (empty($this->data[$field])) {
            $this->addError($field, $message);
        }
        return $this;
    }

    public function addError(string $field, string $message): void
    {
        // Hanya simpan error pertama tiap field.
        $this->errors[$field] ??= $message;
    }

    public function passes(): bool
    {
        return $this->errors === [];
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }
}
