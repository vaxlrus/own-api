<?php

namespace App\Interfaces;

interface RepositoryInterface
{
    public function loadOne;
    public function loadAll;
    public function updateOne;
    public function createOne;
    public function deleteOne;
}