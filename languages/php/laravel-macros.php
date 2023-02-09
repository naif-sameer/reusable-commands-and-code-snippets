
<?php

namespace App\Macros;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;


class CollectionMacros
{
    public static function register()
    {
        /**
         * pagination support for laravel collections, 
         * by default laravel only support pagination on Eloquent/Query builder
         */
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $total ? $this : $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });


        /**
         * @see Should only be used with collections of laravel (Model) classes
         */
        Collection::macro('whereLike', function ($attributes, string $searchTerm) {
            $attributes = Arr::wrap($attributes);

            return $this->filter(function ($item) use ($attributes, $searchTerm) {

                if (!is_array($item) && !is_subclass_of($item, Model::class)) return true;
                
                $item = is_array($item) ? $item : $item?->getAttributes();

                $included = false;
                foreach ($attributes as $attr) {
                    if ($included) break;

                    if (isset($item[$attr]) && str_contains($item[$attr], $searchTerm)) {
                        $included = true;
                    }
                }

                return $included;
            });
        });
    }
}



class EloquentBuilderMacros
{
    public static function register()
    {
        /**
         * search multiple columns using the LIKE operator
         */
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $attributes = Arr::wrap($attributes);

            return $this->where(function (Builder $q) use ($attributes, $searchTerm) {
                foreach ($attributes as $attribute) {
                    $q->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                }
            });
        });
    }
}


// Put your macros file in app/Macros folder

// register the Macros on AppServiceProvider
// example
function boot()
{
    EloquentBuilderMacros::register();
    CollectionMacros::register();

    //...
}
