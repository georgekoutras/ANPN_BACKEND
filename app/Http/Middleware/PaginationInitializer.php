<?php


namespace App\Http\Middleware;

use App\Helpers\PaginatorHelper;
use Closure;
use Illuminate\Pagination\Paginator;


class PaginationInitializer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('pagination') && $request->input('pagination') == 1){
            $requestData = $request->all();
            $requestData['limit'] = $request->input('limit', PaginatorHelper::$REQUEST_LIMIT);
            $requestData['offset'] = $request->input('offset', PaginatorHelper::$REQUEST_OFFSET);
            $request->merge($requestData);
            $page = ($requestData['offset']/$requestData['limit'])+1;
            Paginator::currentPageResolver( function() use ($page){
                return $page;
            });
        }

        return $next($request);
    }
}
