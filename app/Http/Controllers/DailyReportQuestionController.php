<?php

namespace App\Http\Controllers;

use App\Models\DailyReportQuestion;
use Illuminate\Http\Request;

class DailyReportQuestionController extends Controller
{
    public function index(Request $request){

        $questions = DailyReportQuestion::query()->where('active','=',true)->orderBy('label')->get();
        $result['success'] = true;
        $result['data'] = $questions;
        return response()->json($result);
    }
}
