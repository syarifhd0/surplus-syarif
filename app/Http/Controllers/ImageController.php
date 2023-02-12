<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Requests\ImageRequest;
use App\Models\Image;
use Response;

class ImageController extends Controller
{
    use ResponseTrait;

    /**
	 * ImageController constructor.
	 * @param Image $table
	 */
	public function __construct(Image $table)
	{

		$this->table       = $table;
		$this->module_name = 'Image';
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
			/** @var \Illuminate\Database\Eloquent\Builder|\App\Models\Image $query */
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
	 * @param  ImageRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(ImageRequest $request)
	{
		try {
			\DB::beginTransaction();

            $query = $this->table;
            $query->fill($request->except('file'));

			if(!empty($request->file)){
				$image = $this->saveOrReplaceImage($request->file);
				//save url image to db
				$query->file = $image;

				//keep url image to return data
				$request->merge([
					'file' => $image,
				]);
				
			}

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

	public function saveOrReplaceImage($newFile, $oldFile = null){
		if(\File::exists($oldFile)){
			\File::delete($oldFile);
		}

		$folderPath = storage_path($this->table->IMAGE_DIRECTORY);
		$rawImageBase64 = explode(";base64,", $newFile);
		$explodeImage = explode("image/", $rawImageBase64[0]);
		$imageType = $explodeImage[1];
		$imageBase64 = base64_decode($rawImageBase64[1]);
		$file = $folderPath . uniqid() . '.'.$imageType;
		file_put_contents($file, $imageBase64);

		return $file;
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
	 * @param  ImageRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update($id, ImageRequest $request)
	{
		$query = $this->table->find($id);

		if (!$query) {
			return $this->errorResponse('Not found', 404);
		}

		try {
			\DB::beginTransaction();
			$query->fill($request->except('file'));
			
			if(!empty($request->file)){
				$image = $this->saveOrReplaceImage($request->file, $query->file);
				//save url image to db
				$query->file = $image;

				//keep url image to return data
				$request->merge([
					'file' => $image,
				]);
				
			}

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
