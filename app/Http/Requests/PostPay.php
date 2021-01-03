<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostPay extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $paymentMethod = $this->route('paymentMethod');
        $options = collect(json_decode($paymentMethod['options'], true));
        if (is_array($options->first())) {
            $options = $options->keys();
        }

        if ($paymentMethod['id'] !== 'airpay'){
            $rules['option'] = [
                'required',
                Rule::in($options)
            ];
        }

        return $rules;
    }
}
