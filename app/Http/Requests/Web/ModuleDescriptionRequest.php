<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class ModuleDescriptionRequest extends FormRequest
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
        $rules = [
            'module_code' => 'required',
            'heading' => 'nullable',
            'short_description' => 'nullable',
            'description' => 'required',
        ];

        $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png|dimensions:width=1500,height=600|max:2048';

        return $rules;
    }

    public function messages()
    {
        return [
            'module_code.required' => 'Module name is required',
            'image.required' => 'Please select an image.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Only JPG and PNG images are allowed.',
            'image.dimensions' => 'Image dimensions must be exactly 1500x600 pixels.',
            'image.max' => 'Maximum file size is 2MB.',
            'image_order.required' => 'Please enter image order.',
        ];
    }
}
