<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

abstract class FormRequest extends BaseFormRequest
{
    protected array $rules = [];

    abstract public function rules(): array;


    public function authorize(): bool
    {
        return true;
    }

    public function all($keys = null): array
    {
        $data = parent::all();

        $params = $this->route()?->parameters();
        if (empty($params)) {
            return $data;
        }

        foreach ($params as $key => $param) {
            if (is_numeric($param)) {
                $params[$key] = (int) $param;
            }
        }

        return array_merge($data, $params);
    }

    public function withValidator($validator): void
    {
        $this->beforeValidation();

        $validator->after(function ($validator) {
            if ($validator->failed()) {
                return;
            }

            $this->afterValidation($validator->getData());
        });
    }

    protected function beforeValidation(): void
    {
    }

    protected function afterValidation(array $data): void
    {
    }
}
