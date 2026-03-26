<?php

namespace App\Http\Requests;

use App\Models\Domain;

class UpdateDomainRequest extends StoreDomainRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('domain'));
    }
}
