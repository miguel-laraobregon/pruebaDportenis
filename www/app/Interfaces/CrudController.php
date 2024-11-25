<?php

namespace App\Interfaces;

interface CrudController
{
    public function index(): void;
    public function create(): void;
    public function store(array $data): void;
    public function edit(int $id): void;
    public function update(int $id, array $data): void;
    public function destroy(int $id): void;
}
