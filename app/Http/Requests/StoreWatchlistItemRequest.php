<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWatchlistItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // adjust later if policies are added
    }

    public function rules(): array
    {
        return [
            'external_id'  => ['required', 'integer'],
        ];
    }
}
