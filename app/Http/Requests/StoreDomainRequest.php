<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url' => ['required', 'url', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'check_interval' => ['required', 'integer', 'min:1', 'max:1440'],
            'timeout' => ['required', 'integer', 'min:1', 'max:60'],
            'http_method' => ['required', 'in:GET,HEAD'],
            'expected_content' => ['nullable', 'string', 'max:1000'],
            'notify_channel' => ['required', 'in:email,telegram,both,none'],
            'is_active' => ['boolean'],
            'history_days' => ['required', 'integer', 'min:1', 'max:365'],
        ];
    }
}
