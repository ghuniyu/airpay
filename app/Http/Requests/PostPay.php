<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class PostPay extends FormRequest
{
    public Collection $options;

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
        $this->options = collect(json_decode($paymentMethod['options'], true));
        if (is_array($this->options->first())) {
            $this->options = $this->options->keys();
        }

        if ($paymentMethod['id'] !== 'airpay') {
            $rules['option'] = [
                'required',
                Rule::in($this->options)
            ];
        }

        return $rules;
    }

    public function messages()
    {
        $available = $this->options->join(', ');
        return [
            'in' => "The selected option is invalid only : $available are available"
        ];
    }
}
