<?php


namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaginatorHelper
{

    public static $REQUEST_LIMIT = 15;
    public static $REQUEST_OFFSET = 0;

    public static function determineSorting(Request $request, $defaultSorting = []){

        if (($sort = json_decode($request->input('sort'))) !== null){
            $sortArray['sort'] = isset($sort[0]->selector) ? $sort[0]->selector : $defaultSorting['sort'];
            //$sortArray['order'] = isset($sort[0]->desc) && $sort[0]->desc === true ? 'desc' : $defaultSorting['order'];
            if (isset($sort[0]->desc)){
                if ($sort[0]->desc === true){
                    $sortArray['order'] = 'desc';
                }else {
                    $sortArray['order'] = 'asc';
                }
            }else {
                $sortArray['order'] = $defaultSorting['order'];
            }
        }

        return isset($sortArray) ? $sortArray : $defaultSorting;
    }

    public static function getValidator($input = []){
        return Validator::make($input, [
            'limit' => 'sometimes|integer|min:1',
            'offset' => 'sometimes|integer|min:0'
        ]);
    }

    public static function format($results = []){
        unset($results['first_page_url']);
        unset($results['last_page_url']);
        unset($results['next_page_url']);
        unset($results['path']);
        unset($results['prev_page_url']);

        $results['rows'] = $results['data'];
        $results['success'] = true;
        unset($results['data']);

        return $results;
    }
}
