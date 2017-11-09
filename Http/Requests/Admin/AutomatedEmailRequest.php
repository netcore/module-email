<?php

namespace Modules\Email\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Netcore\Translator\Helpers\TransHelper;

class AutomatedEmailRequest extends FormRequest
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

        foreach (TransHelper::getAllLanguages() as $language) {
            $rules['translations.' . $language->iso_code . '.name'] = 'required';
            $rules['translations.' . $language->iso_code . '.text'] = 'required';
        }

        return $rules;
    }

    /**
     * Get the validation messages
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];

        foreach (TransHelper::getAllLanguages() as $language) {
            $messages['translations.' . $language->iso_code . '.name.required'] = 'Name (' . strtoupper($language->iso_code) . ') is required';
            $messages['translations.' . $language->iso_code . '.text.required'] = 'Text (' . strtoupper($language->iso_code) . ') is required';
        }

        return $messages;
    }
}
