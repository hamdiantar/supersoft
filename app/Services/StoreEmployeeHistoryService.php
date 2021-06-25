<?php

namespace App\Services;

use App\Models\Store;
use App\Repositories\StoreEmployeeHistoryRepository;
use App\Traits\LoggerError;
use Exception;

class StoreEmployeeHistoryService
{
    use LoggerError;

    /**
     * @var StoreEmployeeHistoryRepository
     */
    protected $storeEmployeeHistoryRepository;

    public function __construct(StoreEmployeeHistoryRepository $storeEmployeeHistoryRepository)
    {
        $this->storeEmployeeHistoryRepository = $storeEmployeeHistoryRepository;
    }

    public function create(array $historyData, Store $store): bool
    {
        try {
            foreach ($historyData as $data) {
                $this->storeEmployeeHistoryRepository->create([
                    'employee_id' => $data['employee_id'],
                    'store_id' => $store->id,
                    'start' => $data['startDate'],
                    'end' => $data['endDate'],
                ]);
            }
            return true;
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return false;
        }
    }

    public function update(array $historyData, Store $store): bool
    {
        try {
            foreach ($historyData as $data) {
                $storeEmployeeHistory = $this->storeEmployeeHistoryRepository->find($data['id']);
                if ($storeEmployeeHistory) {
                    $this->storeEmployeeHistoryRepository->update([
                        'employee_id' => $data['employee_id'],
                        'store_id' => $store->id,
                        'start' => $data['startDate'],
                        'end' => $data['endDate'],
                    ], $storeEmployeeHistory->id);
                }
            }
            return true;
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return false;
        }
    }
}
