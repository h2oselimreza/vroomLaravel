<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class FooterSliderRequest extends FormRequest
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
            'image_order' => 'required|integer',
        ];

        // If creating (POST), image is required; if updating (PUT), it's optional
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpg,jpeg,png|dimensions:width=415,height=150|max:2048';
        } elseif ($this->isMethod('put')) {
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png|dimensions:width=415,height=150|max:2048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'image.required' => 'Please select an image.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Only JPG and PNG images are allowed.',
            'image.dimensions' => 'Image dimensions must be exactly 1500x600 pixels.',
            'image.max' => 'Maximum file size is 2MB.',
            'image_order.required' => 'Please enter image order.',
        ];
    }
}
