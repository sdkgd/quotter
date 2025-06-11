<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Support\Facades\Auth;

class EditRequest extends FormRequest
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
            'input1'=>'required|max:255',
            'input2'=>'max:255',
            'input3'=>'image|mimes:jpeg,jpg,png,gif|max:1024',
        ];
    }

    public function getInput1()
    {
       return $this->input('input1');
    }

    public function getInput2()
    {
       return $this->input('input2');
    }

    public function getInput3()
    {
       return $this->file('input3');
    }
}
