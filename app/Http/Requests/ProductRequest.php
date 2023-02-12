<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Traits\ResponseTrait;

/**
 * Class ProductRequest
 * @package App\Http\Requests
 */
class ProductRequest extends FormRequest
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
		$id = $this->route('productId');
		$rules = [
			'name'  => 'required|string',
			'description'  => 'required|string',
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
