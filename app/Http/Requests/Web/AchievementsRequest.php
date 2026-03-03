<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class AchievementsRequest extends FormRequest
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
            'heading' => 'required',
            'short_description' => 'required',
            'details' => 'required',
            'date' => 'required|date|date_format:Y-m-d',
            'is_active' => 'required',
        ];

        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        } elseif ($this->isMethod('put')) {
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'image.required' => 'Please select an image.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Only JPG and PNG images are allowed.',
            'image.dimensions' => 'Image dimensions must be exactly 400x300 pixels.',
            'image.max' => 'Maximum file size is 2MB.',
        ];
    }
}
