<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Traits\ResponseTrait;

/**
 * Class CategoryRequest
 * @package App\Http\Requests
 */
class CategoryRequest extends FormRequest
{
	use ResponseTrait;

	protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
	{
		$response = $this->errorResponse('The given data was invalid.', 422, $validator->errors());

		throw new \Illuminate\Validation\ValidationException($validator, $response);
	}

	/**
	 * @return mixed
	 */
	public function rules()
	{
		$id = $this->route('categoryId');
		$rules = [
			'name'  => 'required|string',
			'enable'      => 'required|boolean'
        ];
        
		return $rules;
	}

	/**
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * @return array
	 */
	public function messages()
	{
		return [
			'enable.boolean'        => 'The :attribute is not a valid . Valid boolean : (0,1)',
		];
	}

	/**
	 * Prepare before Validation
	 */
	protected function prepareForValidation()
	{
		
	}
}
