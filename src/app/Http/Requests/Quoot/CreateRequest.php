<?php

namespace App\Http\Requests\Quoot;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Support\Facades\Auth;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::id()) return true;
        else return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'quoot'=>'required|max:140'
        ];
    }

    public function getQuoot()
    {
        return $this->input('quoot');
    }

    public function getUserId(){
        return $this->user()->id;
    }
}
