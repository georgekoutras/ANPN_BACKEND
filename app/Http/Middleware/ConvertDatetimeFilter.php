<?php


namespace App\Http\Middleware;


use Carbon\Carbon;
use Closure;

class ConvertDatetimeFilter
{

    private $dateFields = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('filter')){
            if (($decodedFilters = json_decode($request->input('filter'))) != null){
                $request->header('Access-Control-Allow-Origin', '*');
                $this->parseFilters($request, $decodedFilters);
                $request->merge([
                    'filter' => json_encode($decodedFilters)
                ]);
            }
        }

        return $next($request);
    }

    private function parseFilters($request, &$decoded){
        foreach($decoded as $index => $item){
            if (is_string($item)) {
                if ($index == 0) {
                    if (in_array($item, $this->dateFields)){
                        try {
                            $carbon = Carbon::createFromFormat("Y/m/d H:i:s", $decoded[2], $request->user()->timezone);
                            $carbon->setTimezone('UTC');
                            $decoded[2] = $carbon->format("Y-m-d H:i:s");
                        }catch (\Exception $e){

                        }
                    }
                }
                continue;
            }
            if (is_array($item)){
                $this->parseFilters($request, $item);
            }
        }
    }

}
