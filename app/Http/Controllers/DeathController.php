<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Deaths;
use App\Models\Patients;
use App\Models\Reading;
use App\Models\Treatments;
use App\Traits\FormatDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DeathController extends Controller
{
    use FormatDates;

    public function index()
    {
        if(Auth::check()) {
            $this->authorize('index',[Deaths::class]);

            $accounts = Accounts::query()
                ->join('patients','patients.account_id','=','accounts.id')
                ->join('deaths', 'deaths.patient_id','=','patients.id')
                ->whereNull('deaths.deleted_at');


            $accounts = $accounts->select([
                'accounts.first_name',
                'accounts.last_name',
                'accounts.id',
                'accounts.role',
                'accounts.email',
                'patients.id as patient_id',
                'deaths.date',
                'deaths.id as death_id',
                DB::raw("concat(accounts.first_name,' ',accounts.last_name) as full_name")
            ])->orderBy('date','desc')->get();

            foreach ($accounts as $dr){
                $dr->is_for_field = true;            }
            return view('admin.deaths.index', ['items' => $accounts]);
        }else{
            return redirect('login');
        }

    }

    public function create(){
        if(Auth::check()) {
            $this->authorize('create', [Deaths::class]);

            return view('admin.deaths.create',[
                'formAction' => 'create']);
        }else{
            return redirect('login');
        }
    }

    public function store(Request $request)
    {
        if(Auth::check()) {

            $validator = Validator::make($request->all(),[
                'patient_id' => [
                    'required',
                    'int',
                    Rule::exists('patients', 'id')->whereNull('deleted_at'),
                    function($attribute, $value, $fail) use($request){
                        if (count($death = Deaths::where('patient_id','=',$request->input('patient_id'))->whereNull('deleted_at')->get()) > 0) {
                            return $fail("Υπάρχει καταγεγραμμένος θάνατος γι αυτόν τον ασθενή.");
                        }
                    }
                ],
                'date' => [
                    'required',
                    'date_format:d/m/y',
                ],
                'cardiovascular' => 'nullable|int|max:1, min:0',
                'respiratory' => 'nullable|int|max:1, min:0',
                'infectious_disease' => 'nullable|int|max:1, min:0',
                'malignancy' => 'nullable|int|max:1, min:0',
                'notes'=>'nullable|string|max:12000'

            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $this->authorize('store', [Deaths::class, intval($request->input('patient_id'))]);

                $record = new Deaths();
                $record->fill($request->all());
                $record->date = $record->convertDate($request->input('date'), 'Europe/Athens');
                $result['success'] = $record->save();
                $accounts = Accounts::query()
                    ->join('patients','patients.account_id','=','accounts.id')
                    ->join('deaths', 'deaths.patient_id','=','patients.id');
                $accounts = $accounts->select([
                    'accounts.first_name',
                    'accounts.last_name',
                    'accounts.id',
                    'accounts.role',
                    'accounts.email',
                    'deaths.date',
                    'deaths.id as death_id'
                ]);
                if($result['success'])
                    return redirect()->route('deaths.patients', ['items' => $accounts->orderBy('date','desc')->get()])->with('success', 'Ο θάνατος προστέθηκε επιτυχώς!');
                else
                    return redirect()->route('deaths.patients', ['items' => $accounts->orderBy('date','desc')->get()])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }

    public function edit(Request $request, Patients $patient, Deaths $death)
    {

        if(Auth::check()) {
            $this->authorize('update',[Deaths::class, $patient]);

            $death->is_for_field = true;
            return view('admin.deaths.edit',[
                'formAction' => 'edit',
                'data' => $death,
                'patientId'=>$patient->id,
                'patientName'=> $patient->account->first_name.' '.$patient->account->last_name,]);
        }else{
            return redirect('login');
        }
    }

    public function update(Request $request, Patients $patient, Deaths $death)
    {


        if(Auth::check()) {
            $this->authorize('update',[Deaths::class, $patient]);

            $validator = Validator::make($request->all(),[
                'date' => [
                    'required',
                    'date_format:d/m/y',
                ],
                'cardiovascular' => 'nullable|int|max:1, min:0',
                'respiratory' => 'nullable|int|max:1, min:0',
                'infectious_disease' => 'nullable|int|max:1, min:0',
                'malignancy' => 'nullable|int|max:1, min:0',
                'notes'=>'nullable|string|max:12000'

            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $death->fill($request->all());
                $death->date = $death->convertDate($request->input('date'), 'Europe/Athens');

                $result['success'] = $death->save();
                $deaths = Accounts::query()
                    ->join('patients','patients.account_id','=','accounts.id')
                    ->join('deaths', 'deaths.patient_id','=','patients.id')
                    ->whereNull('deaths.deleted_at');
                $deaths = $deaths->select([
                    'accounts.first_name',
                    'accounts.last_name',
                    'accounts.id',
                    'accounts.role',
                    'accounts.email',
                    'deaths.date',
                    'deaths.id as death_id'
                ])->orderBy('date','desc')->get();
                if($result['success'])
                    return redirect()->route('deaths.patients', ['items' => $deaths])->with('success', 'Ο θάνατος ανανεώθηκε επιτυχώς!');
                else
                    return redirect()->route('deaths.patients', ['items' => $deaths])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }

    public function destroy(Request $request, Patients $patient, Deaths $death)
    {
        $this->authorize('delete', [Deaths::class, $patient]);
        $death->is_for_field = true;
        $patientName = $patient->first_name.' '.$patient->last_name;

        $result['success'] = $death->delete();

        $deaths = Accounts::query()
            ->join('patients','patients.account_id','=','accounts.id')
            ->join('deaths', 'deaths.patient_id','=','patients.id')
            ->whereNull('deaths.deleted_at');
        $deaths = $deaths->select([
            'accounts.first_name',
            'accounts.last_name',
            'accounts.id',
            'accounts.role',
            'accounts.email',
            'deaths.date',
            'deaths.id as death_id'
        ])->orderBy('date','desc')->get();

        return redirect()->route('deaths.patients', ['items' => $deaths])->with('success', 'Ο θάνατος για '.$patientName.' διαγράφηκε επιτυχώς!');

    }
}
