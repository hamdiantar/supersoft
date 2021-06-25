<?php

namespace App\Repositories\Interfaces;

/**
 * Main Repository interface
 *
 * Interface RepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface RepositoryInterface
{

    /**
     * Function returns model based on its ID
     *
     * @param int|string $id
     * @param array $columns
     */
    public function find($id, array $columns = ['*']);

    /**
     * Function returns model based on search criteria
     */
    public function findBy(array $criteria, array $columns = ['*']);

    /**
     * Function returns first model based on search criteria
     */
    public function findOneBy(array $criteria, array $columns = ['*']);

    /**
     * Function returns all data from DB
     */
    public function findAll(array $columns = ['*']);

    /**
     * Creates new record in DB
     */
    public function create(array $data);

    /**
     * Updates record in DB
     */
    public function update(array $data, $id);

    /**
     * Deletes record from DB based on it ID
     *
     * @param int|string $id
     */
    public function delete($id);

    /**
     * Insert new record or multiple record in DB
     */
    public function insert(array $data);
}
