<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScrapeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'gender' => 'required|in:heren,dames,jongens,meisjes',
            'category'=>'nullable|in:schoenen,tassen,accessoires',
            'sort'=>'nullable|in:prijs-laag-hoog,prijs-hoog-laag,nieuwste,populairste'
            //
        ];
    }
}
