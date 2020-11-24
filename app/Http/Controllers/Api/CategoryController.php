<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Categories\CategoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(CategoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        //
        $subjects = $this->repository->listCategory($request);
        if (!$subjects['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($subjects['data']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    public function category()
    {
        $categories =  $this->repository->categories();
        if (!$categories['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess($categories['data']);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categories =  $this->repository->deleteCategory($id);
        if (!$categories['success']) {
            return $this->sendError(500, "ERROR", "500");
        }

        return $this->sendSuccess("DELETE SUBJECT SUCCESS");
    }
}
