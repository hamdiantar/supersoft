<?php

namespace App\Repositories\Interfaces;

/**
 * Interface OrderInterface
 * @package App\Repositories\Interfaces
 */
interface OrderByInterface
{
    /**
     * Get column for ordering
     */
    public function getColumn(): string;

    /**
     * Get order direction: ASC, DESC
     */
    public function getDirection(): string;
}
