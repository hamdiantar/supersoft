<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class AbstractController extends Controller
{
    abstract public function getSortFields(): array;

    public function implementDataTableSearch(Builder $builder, Request $request): Builder
    {
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sortBy = $request->sort_by;
            $sortMethod = $this->getSortMethod($request);
            $sorFields = $this->getSortFields();
            if (authIsSuperAdmin()) {
                $sorFields['branch'] = 'branch_id';
            }
           return $builder->orderBy($sorFields[$sortBy], $sortMethod);
        }
        return $builder->orderBy('id', 'DESC');
    }

    protected function getLang(): string
    {
        return app()->getLocale() == 'ar' ? 'ar' : 'en';
    }

    protected function sortMethods(): array
    {
        return ['desc', 'asc'];
    }

    protected function getSortMethod(Request $request): string
    {
        $sortMethod = $request->has('sort_method') ? $request->sort_method : 'asc';
        if (!in_array($sortMethod, $this->sortMethods())) {
            $sortMethod = 'desc';
        }
        return $sortMethod;
    }
}
