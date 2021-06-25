<?php

namespace App\Repositories\Interfaces;

/**
 * Universal interface for pagination.
 * In case when getLastId exists it is taken starting from this ID (much faster)
 * Other case it is using regular offset
 *
 * Interface PaginationInterface
 * @package App\Repositories\Interfaces
 */
interface PaginationInterface
{
    /**
     * Get limit for pagination
     */
    public function getLimit(): int;

    /**
     * Get offset for pagination
     */
    public function getOffset(): int;

    /**
     * Get last ID
     */
    public function getLastId(): int;
}
