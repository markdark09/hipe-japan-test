<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface IBaseRepository
{
    /**
     * Gel all models
     * 
     * @param array $relations
     * @return Collection
     */
    public function all(array $relations = []): Collection;

    /**
     * Get all trashed models
     * 
     * @return Collection
     */
    public function allTrashed(): Collection;

    /**
     * Find model by id
     * 
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model
     */
    public function findById(
        int $modelId, 
        array $columns = ['*'], 
        array $relations = [], 
        array $appends = []
    ) :? Model;

    /**
     * Find trashed model by id.
     * 
     * @param int $modelId
     * @return Model
     */
    public function findTrashedById(int $modelId):? Model;

    /**
     * Find only trashed model by id.
     * 
     * @param int $modelId
     * @return Model
     */
    public function findOnlyTrashedById(int $modelId):? Model;

    /**
     * Store data
     * 
     * @param array $payload
     * @return Model
     */
    public function create(array $payload):? Model;
    
    /**
     * Update or Store data
     * 
     * @param int $modelId
     * @param array $payload
     * @return Model
     */
    public function updateOrCreate(int $modelId = null, array $payload):? Model;

    /** 
     * Update existing model
     * 
     * @param int $modelId
     * @param array $payload
     * @return Model
     */
    public function update(int $modelId, array $payload):? Model;
    
    /**
     * Delete data by id
     * 
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool;

    /**
     * Restore data by id
     * 
     * @param int $modelId
     * @return Model
     */
    public function restoreById(int $modelId):? Model;

    /**
     * Permanently deleted model by id.
     * 
     * @param int $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool;
}