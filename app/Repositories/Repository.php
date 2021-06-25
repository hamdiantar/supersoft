<?php

namespace App\Repositories;

use App\Repositories\Interfaces\OrderByInterface;
use App\Repositories\Interfaces\PaginationInterface;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Abstract repository which all other repositories inherit
 *
 * Class Repository
 * @package App\Repositories
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class Repository implements RepositoryInterface
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var Eloquent|Model|AppModel
     */
    protected $model;

    /**
     * @throws RepositoryException
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
        $this->init();
    }

    /**
     * Specify Model class name
     */
    abstract public function model(): string;

    /**
     * Init repository.
     */
    protected function init()
    {
    }

    /**
     * Inits model
     *
     * @throws RepositoryException
     */
    public function makeModel(): Model
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an "
                . "instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * {@inheritDoc}
     *
     * @return Collection|Model[]|AppModel[]
     */
    public function findAll(array $columns = ['*'])
    {
        return $this->findBy([], $columns);
    }

    /**
     * {@inheritDoc}
     *
     * @return Model|AppModel
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritDoc}
     *
     * @param array $data
     * @param int|string $id
     * @param string $attribute
     * @return bool|int
     */
    public function update(array $data, $id, string $attribute = 'id')
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|int $id
     * @return int
     */
    public function delete($id): int
    {
        return $this->model->destroy($id);
    }

    /**
     * {@inheritDoc}
     *
     * @param string|int $id
     * @param array $columns
     * @return Model|AppModel|null
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * {@inheritDoc}
     *
     * @param array $criteria
     * @param array $columns
     * @return Model|AppModel|null
     */
    public function findOneBy(array $criteria, array $columns = ['*'])
    {
        $builder = $this->model->query();

        foreach ($criteria as $key => $value) {
            $operator = '=';

            if (is_array($value)) {
                $operator = $value['operator'];
                $value = $value['value'];
            }
            $builder->where($key, $operator, $value);
        }

        return $builder->first($columns);
    }

    /**
     * {@inheritDoc}
     *
     * @param array $criteria
     * @param array $columns
     * @return Collection|Model[]|AppModel[]
     */
    public function findBy(array $criteria, array $columns = ['*'])
    {
        $builder = $this->model->query();

        foreach ($criteria as $key => $value) {
            $operator = '=';

            if (is_array($value)) {
                $operator = $value['operator'];
                $value = $value['value'];
            }
            $builder->where($key, $operator, $value);
        }

        return $builder->get($columns);
    }

    /**
     * Upsert function for creating or updating record if exists
     *
     * @param array $data
     * @param string|int $id
     * @param string $attribute
     * @return AppModel|bool|Model|int
     */
    public function updateOrCreate(array $data, $id, string $attribute = 'id')
    {
        if ($model = $this->findOneBy([$attribute => $id])) {
            return $model->update($data, $id, $attribute);
        }

        return $this->create($data);
    }

    /**
     * Helper method to use PaginationInterface for pagination
     *
     * @param Builder $builder
     * @param PaginationInterface $pagination
     * @return AppModel[]|Collection|Model[]
     */
    public function paginate(Builder $builder, PaginationInterface $pagination)
    {
        return $this->paginateBuilder($builder, $pagination)->get();
    }

    /**
     * Helper method making pagination using PaginationInterface [returns Builder]
     *
     * @param Builder $builder
     * @param PaginationInterface $pagination
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function paginationBuilder(Builder $builder, PaginationInterface $pagination)
    {
        /**
         * @var \Illuminate\Database\Query\Builder $builder
         */
        $builder->limit($pagination->getLimit());

        if ($pagination->getLastId()) {
            $builder->where('h_id', '<', $pagination->getLastId());
        } elseif ($pagination->getOffset()) {
            $builder->offset($pagination->getOffset());
        }

        return $builder;
    }

    /**
     * Helper method for ordering using OrderByInterface
     *
     * @param Builder $builder
     * @param OrderByInterface $order
     */
    public function orderBy(Builder $builder, OrderByInterface $order)
    {
        return $builder->orderBy($order->getColumn(), $order->getDirection());
    }

    /**
     * {@inheritDoc}
     *
     * @return Model|AppModel
     */
    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * {@inheritDoc}
     *
     * @param string column
     * @param array $array
     * @return Collection|Model[]|AppModel[]
     */
    public function whereIn(string $column, array $values)
    {
        return $this->model->whereIn($column, $values)->get();
    }
}
