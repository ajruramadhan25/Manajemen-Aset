<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($this->category)],
            'parent_category_id' => 'nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Nama kategori sudah digunakan.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $name = trim((string) $this->input('name', ''));
            if ($name === '') {
                return;
            }

            $normalized = mb_strtolower($name, 'UTF-8');
            $categoryId = $this->route('category')?->id;

            $query = \App\Models\Category::query()
                ->whereRaw('LOWER(name) = ?', [$normalized]);

            if ($categoryId) {
                $query->where('id', '!=', $categoryId);
            }

            if ($query->exists()) {
                $validator->errors()->add('name', 'Nama kategori sudah digunakan (tidak boleh sama walau beda huruf besar/kecil).');
            }
        });
    }
}
