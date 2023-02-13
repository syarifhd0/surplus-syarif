<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductImageRequest;
use App\Http\Requests\CategoryProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\ProductImage;
use App\Models\Image;
use Response;

class ProductController extends Controller
{
    use ResponseTrait;

    /**
	 * ProductController constructor.
	 * @param Product $table
	 */
	public function __construct(Product $table)
	{

		$this->table       = $table;
		$this->module_name = 'Product';
		$this->sub_module_name = 'Category Product';
	}

    /**
	 * Display a listing of the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(Request $request)
	{
		try {
			/** @var \Illuminate\Database\Eloquent\Builder|\App\Models\Product $query */
			$query = $this->table->select();

			if ($request->has('trashed') && $request->get('trashed')) {
				$query = $query->withTrashed();
			}

			if ($request->has('prop')) {
				$query->orderBy($request->get('prop'), $request->get('dir', 'DESC'));
			} else {
				$query->orderBy('id', $request->get('dir', 'DESC'));
			}

			if ($request->has('enable')) {
				$query->where('enable', "{$request->get('enable')}");
			}
			if ($request->has('global_search')) {
				$query->where(function ($q) use ($request) {
					$q->where('name', 'like', "%{$request->get('global_search')}%");
				});
			}
            
			return $this->successResponse($query->get(),200);
		} catch (\Exception $e) {
			return $this->errorResponse('Error Displaying ' . $this->module_name,500);
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  ProductRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(ProductRequest $request)
	{
		try {
			\DB::beginTransaction();

            $query = $this->table;
            $query->fill($request->all());
			
			if($query->save()){
				\DB::commit();
				return $this->successResponse($request->all(),201);
			}

		} catch (\Exception $e) {
			\DB::rollBack();
			return $this->errorResponse('Internal Error Creating ' . $this->module_name,500);
		} catch (\Throwable $e) {
			\DB::rollBack();
			return $this->errorResponse('Error Creating ' . $this->module_name,500);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
		$data = $this->table->find($id);
		if (!$data) {
			return $this->errorResponse('Not found', 404);
		}

		return $this->successResponse($data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  ProductRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update($id, ProductRequest $request)
	{
		$query = $this->table->find($id);

		if (!$query) {
			return $this->errorResponse('Not found', 404);
		}

		try {
			\DB::beginTransaction();

            $query->fill($request->all());
			
			if($query->save()){
				\DB::commit();
				return $this->successResponse($request->all(),200);
			}

		} catch (\Exception $e) {
			\DB::rollBack();
			return $this->errorResponse('Internal Error Updating ' . $this->module_name,500);
		} catch (\Throwable $e) {
			\DB::rollBack();
			return $this->errorResponse('Error Updating ' . $this->module_name,500);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy($id)
	{
		$query = $this->table->find($id);
		if (!$query) {
			return $this->errorResponse('Not found', 404);
		}

		try {
			if($query->delete()){
				\DB::commit();
				return $this->successResponse(['id' => $id],200);
			}
		} catch (\Exception $e) {
			\DB::rollBack();
			return $this->errorResponse('Internal Error Deleting ' . $this->module_name,500);
		} catch (\Throwable $e) {
			\DB::rollBack();
			return $this->errorResponse('Error Deleting ' . $this->module_name,500);
		}
	}

	
	/**
	 * Display a listing of the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getCategoryProduct($productId, Request $request)
	{
		$model = $this->table->find($productId);
		if (!$model) {
			return $this->errorResponse('Not found', 404);
		}

		try {
			$query = new Category;
			$query = $query->whereHas('category_product',function($q) use ($productId){
				$q->where('product_id',$productId);
			});
            
			return $this->successResponse($query->get(),200);
		} catch (\Exception $e) {
			return $this->errorResponse('Error Displaying ' . $this->sub_module_name,500);
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function postCategoryProductById($productId, $categoryId, CategoryProductRequest $request)
	{
		$model = $this->table->find($productId);

		if (!$model) {
			return $this->errorResponse('Not found', 404);
		}

		try {
			\DB::beginTransaction();

			$attribute = [
				'category_id' => $categoryId
			];
			$query = $model->category_product()->updateOrCreate($attribute,$attribute);

			if($query){
				\DB::commit();
				return $this->successResponse(['category_id' => $categoryId],201);
			}

		} catch (\Exception $e) {
			\DB::rollBack();
			return $this->errorResponse('Internal Error Creating ' . $this->sub_module_name,500);
		} catch (\Throwable $e) {
			\DB::rollBack();
			return $this->errorResponse('Error Creating ' . $this->sub_module_name,500);
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteCategoryProductById($productId, $categoryId, Request $request)
	{
		$model = $this->table->find($productId);
		if (!$model) {
			return $this->errorResponse('Not found', 404);
		}

		try {
			
			$query = $model->category_product()->where('category_id',$categoryId)->delete();
			if($query){
				\DB::commit();
				return $this->successResponse(['category_id' => $categoryId],200);
			}
		} catch (\Exception $e) {
			\DB::rollBack();
			return $this->errorResponse('Internal Error Deleting ' . $this->sub_module_name,500);
		} catch (\Throwable $e) {
			\DB::rollBack();
			return $this->errorResponse('Error Deleting ' . $this->sub_module_name,500);
		}
	}

	/**
	* Display a listing of the resource.
	*
	* @param \Illuminate\Http\Request $request
	* @return \Illuminate\Http\JsonResponse
	*/
   public function getProductImage($productId, Request $request)
   {
	   $model = $this->table->find($productId);
	   if (!$model) {
	   		return $this->errorResponse('Not found', 404);
	   }

	   try {
		   $query = new Image;
		   $query = $query->whereHas('product_image',function($q) use ($productId){
			   $q->where('product_id',$productId);
		   });
		   
		   return $this->successResponse($query->get(),200);
	   } catch (\Exception $e) {
		   return $this->errorResponse('Error Displaying ' . $this->sub_module_name,500);
	   }
   }

   /**
	* Display a listing of the resource.
	*
	* @param \Illuminate\Http\Request $request
	* @return \Illuminate\Http\JsonResponse
	*/
   public function postProductImageById($productId, $imageId, ProductImageRequest $request)
   {
	   $model = $this->table->find($productId);

	   if (!$model) {
		   return $this->errorResponse('Not found', 404);
	   }

	   try {
		   \DB::beginTransaction();

		   $attribute = [
			   'image_id' => $imageId
		   ];
		   $query = $model->product_image()->updateOrCreate($attribute,$attribute);

		   if($query){
			   \DB::commit();
			   return $this->successResponse(['image_id' => $imageId],201);
		   }

	   } catch (\Exception $e) {
		   \DB::rollBack();
		   return $this->errorResponse('Internal Error Creating ' . $this->sub_module_name,500);
	   } catch (\Throwable $e) {
		   \DB::rollBack();
		   return $this->errorResponse('Error Creating ' . $this->sub_module_name,500);
	   }
   }

   /**
	* Display a listing of the resource.
	*
	* @param \Illuminate\Http\Request $request
	* @return \Illuminate\Http\JsonResponse
	*/
   public function deleteProductImageById($productId, $imageId, Request $request)
   {
	   $model = $this->table->find($productId);
	   if (!$model) {
		   return $this->errorResponse('Not found', 404);
	   }

	   try {
		   
		   $query = $model->product_image()->where('image_id',$imageId)->delete();
		   if($query){
			   \DB::commit();
			   return $this->successResponse(['image_id' => $imageId],200);
		   }
	   } catch (\Exception $e) {
		   \DB::rollBack();
		   return $this->errorResponse('Internal Error Deleting ' . $this->sub_module_name,500);
	   } catch (\Throwable $e) {
		   \DB::rollBack();
		   return $this->errorResponse('Error Deleting ' . $this->sub_module_name,500);
	   }
   }
}
