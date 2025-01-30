<?php

namespace App\Http\Requests\Discount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:total_amount,category_quantity,category_multiple',
            'category_id' => 'required_unless:type,total_amount|exists:categories,id|nullable',
            'min_amount' => 'required_if:type,total_amount|numeric|min:0|nullable',
            'min_quantity' => 'required_if:type,category_quantity|integer|min:1|nullable',
            'discount_rate' => 'required_if:type,total_amount,category_multiple|numeric|between:0,100|nullable',
            'free_items' => 'required_if:type,category_quantity|integer|min:1|nullable',
            'is_active' => 'boolean',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
} 