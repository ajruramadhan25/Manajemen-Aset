<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'asset_code' => [
                'required',
                'string',
                Rule::unique('assets', 'asset_code')->ignore($this->asset),
            ],
            'quantity' => 'required|integer|min:1',
            'parent_category_id' => 'nullable|exists:categories,id',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:available,deployed,maintenance,broken',
            'purchase_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'useful_life' => 'required|integer|min:1|max:100',
            'image' => 'nullable|image|max:2048',
            'unit_identifiers' => 'nullable|array',
            'unit_identifiers.*' => 'nullable|string|max:255',
        ];
    }
    }

