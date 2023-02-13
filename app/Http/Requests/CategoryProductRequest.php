<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Traits\ResponseTrait;
use App\Models\Product;

/**
 * Class CategoryProductRequest
 * @package App\Http\Requests
 */
class CategoryProductRequest extends FormRequest
{
	use ResponseTrait;

	protected function withValidator(\Illuminate\Contracts\Validation\Validator $validator)
	{
        $categoryId = !empty($this->route('categoryId'))?$this->route('categoryId'):null;
        $productId = !empty($this->route('productId'))?$this->route('productId'):null;
        
        $validator->after(function ($validator) use($categoryId,$productId){
            if(empty(Category::find($categoryId))){
                $validator->errors()->add('{categoryId}', 'data not found');
            }

            if(empty(Product::find($productId))){
                $validator->errors()->add('{productId}', 'data not found');
            }
        });

	}

	/**
	 * @return mixed
	 */
	public function rules()
	{
        $rules = [];

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
			
		];
	}

	/**
	 * Prepare before Validation
	 */
	protected function prepareForValidation()
	{
		
	}
}
