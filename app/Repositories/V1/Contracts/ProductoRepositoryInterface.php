<?php

namespace App\Repositories\V1\Contracts;

interface ProductoRepositoryInterface
{
    public function getAll();

    public function create(array $data);

    public function find($id);

    public function update(array $data, $id);

    public function delete($id);
}
