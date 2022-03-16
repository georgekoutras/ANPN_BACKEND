<?php

namespace App\Http\Controllers\Mobile;

use App\Helpers\PaginatorHelper;
use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Deaths;
use App\Models\Patients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientControllerMobile extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Patients::class);
        $requestUser = $request->user();

        $input = $request->all();
        $validator = PaginatorHelper::getValidator($input);

        if ($validator->fails()) {
            return response()->json(['rows' => [], 'total' => 0]);
        }

        $sortArray = PaginatorHelper::determineSorting($request, Accounts::DEFAULT_SORTING());

        switch ($sortArray['sort']) {
            case "id":
                $sortArray['sort'] = 'patients.id';
                break;
            case "full_name":
                $sortArray['sort'] = DB::raw("concat(accounts.last_name,' ',accounts.first_name)");
                break;
            case "email":
                $sortArray['sort'] = 'accounts.email';
                break;
            case "role":
                $sortArray['sort'] = 'accounts.role';
                break;

        }

        if ($requestUser->isDoctor()){
            $patients = Patients::query()->where('doctor_id', '=', $requestUser->id)
                ->leftJoin('accounts', 'accounts.id', '=', 'patients.account_id');
        }else if ($requestUser->isAdmin()){
            $patients = Patients::query()->leftJoin('accounts', 'accounts.id', '=', 'patients.account_id');
        }else{
            return response()->json([], 200);
        }

        if($request->has('search')){
            $patients = $patients->where(function ($query) use ($request) {
                $query->where('accounts.last_name', 'LIKE', "%" . $request->input('search') . '%')
                    ->orWhere('accounts.first_name', 'LIKE', "%" . $request->input('search') . '%')
                    ->orWhere('patients.social_id', 'LIKE', "%" . $request->input('search') . '%');
            });
        }
        $patients = $patients->select([
            'patients.id',
            'patients.social_id',
            'patients.birth_date',
            'accounts.id as account_id',
            'accounts.first_name',
            'accounts.second_name',
            'accounts.last_name',
            'accounts.email',
            'accounts.role',
            'accounts.mobile','accounts.notification_enabled','accounts.notification_mode',
            DB::raw("concat(accounts.last_name,' ',accounts.first_name) as full_name")]);

        $patients = $patients->orderBy($sortArray['sort'], $sortArray['order']);
        $patients = $patients->paginate($request->input('limit'))->toArray();

        $result['success'] = true;
        $result['data'] = PaginatorHelper::format($patients);

        return response()->json($result, 200);
    }

    public function searchPatientNameSocial(Request $request){

        $patients = [];
        if($request->user()->role() == 'doctor') {
            $patients = Accounts::query()
                ->join('patients','patients.account_id','=','accounts.id')
                ->where('patients.doctor_id','=',$request->user()->id)
                ->select(['patients.id as patient_id',
                    'patients.social_id',
                    'accounts.id as account_id',
                    'accounts.first_name',
                    'accounts.last_name',
                    'accounts.second_name',
                    DB::raw("concat(accounts.last_name,' ',accounts.first_name) as full_name")])
                ->where(function ($query) use ($request) {
                    $query->where('accounts.last_name', 'LIKE', "%" . $request->input('search') . '%')
                        ->orWhere('accounts.first_name', 'LIKE', "%" . $request->input('search') . '%')
                        ->orWhere('patients.social_id', 'LIKE', "%" . $request->input('search') . '%');
                })
                ->get();
        }

        return response()->json($patients);

    }
}
