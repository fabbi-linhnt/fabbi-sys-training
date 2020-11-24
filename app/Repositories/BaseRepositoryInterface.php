<?php


namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function getAll();
    public function getItemById($id);
    public function createItem($request);
    public function editItem($id, $request);
    public function destroyItem($id);
}
