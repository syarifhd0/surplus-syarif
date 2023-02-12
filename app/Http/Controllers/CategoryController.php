<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Response;

class CategoryController extends Controller
{
    use ResponseTrait;

    /**
	 * CategoryController constructor.
	 * @param Category $table
	 */
	public function __construct(Category $table)
	{

		$this->table       = $table;
		$this->module_name = 'Category';
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
			/** @var \Illuminate\Database\Eloquent\Builder|\App\Models\Category $query */
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
	 * @param  CategoryRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(CategoryRequest $request)
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
	 * @param  CategoryRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update($id, CategoryRequest $request)
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
}
