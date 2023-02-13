<?php

namespace App\Http\Requests;

use App\Models\Image;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Traits\ResponseTrait;
use App\Models\Product;

/**
 * Class ProductImageRequest
 * @package App\Http\Requests
 */
class ProductImageRequest extends FormRequest
{
	use ResponseTrait;

	protected function withValidator(\Illuminate\Contracts\Validation\Validator $validator)
	{
        $imageId = !empty($this->route('imageId'))?$this->route('imageId'):null;
        $productId = !empty($this->route('productId'))?$this->route('productId'):null;
        
        $validator->after(function ($validator) use($imageId,$productId){
            if(empty(Image::find($imageId))){
                $validator->errors()->add('{imageId}', 'data not found');
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
