<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\CatQuestion;
use App\Models\Cats;
use App\Models\CcqQuestion;
use App\Models\Ccqs;
use App\Models\Patients;
use App\Traits\FormatDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CcqController extends Controller
{
    use FormatDates;

    public function index(Request $request)
    {
        if(Auth::check()) {
            $this->authorize('index',[Ccqs::class]);

            if($request->user()->role == 'administrator') {
                $accounts = Accounts::query()->join('patients','patients.account_id','=','accounts.id');
            }else if ($request->user()->role == 'doctor') {
                $accounts = Accounts::query()->join('patients', 'patients.account_id', '=', 'accounts.id')
                    ->where('patients.doctor_id', '=', $request->user()->id);
            }
            $accounts = $accounts->select([
                'accounts.first_name',
                'accounts.last_name',
                'accounts.id',
                'accounts.role',
                'accounts.email',
                'patients.id as patient_id',
            ]);
            return view('admin.ccqs.index', ['items' => $accounts->get()]);
        }else{
            return redirect('login');
        }

    }

    public function patientCcqs(Patients $patient){
        if(Auth::check()) {
            $this->authorize('patientCcqs', [Ccqs::class, $patient]);

            $ccqs = Ccqs::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();
            foreach ($ccqs as $dr){
                $dr->is_for_field = true;
            }

            return view('admin.ccqs.patient_ccqs', [
                'items' => $ccqs,
                'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
                'patiendId'=>$patient->id]);
        }else{
            return redirect('login');
        }
    }

    public function create(Patients $patient)
    {
        if(Auth::check()) {
            $this->authorize('patientCcqs', [Ccqs::class, $patient]);

            $ccqQuestion = CcqQuestion::query()->orderBy('id')->get();
            return view('admin.ccqs.create',[
                'formAction' => 'create',
                'questions' => $ccqQuestion,
                'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
                'patientId'=>$patient->id,]);
        }else{
            return redirect('login');
        }
    }

    public function store(Request $request, Patients $patient)
    {
        if(Auth::check()) {
            $this->authorize('store', [Ccqs::class, $patient]);

            $validator = Validator::make($request->all(),[
                'patient_id' => [
                    'required',
                    'int',
                    Rule::exists('patients', 'id')->whereNull('deleted_at'),
                ],
                'q1' => 'required|int|max:6, min:0',
                'q2' => 'required|int|max:6, min:0',
                'q3' => 'required|int|max:6, min:0',
                'q4' => 'required|int|max:6, min:0',
                'q5' => 'required|int|max:6, min:0',
                'q6' => 'required|int|max:6, min:0',
                'q7' => 'required|int|max:6, min:0',
                'q8' => 'required|int|max:6, min:0',
                'q9' => 'required|int|max:6, min:0',
                'q10' => 'required|int|max:6, min:0',
                'diagnose_date' => [
                    'required_if:role,patient',
                    'date_format:d/m/y',
                ],
                'status' =>  [
                    'required',
                    'string',
                    Rule::in(['baseline','exacerbation']),
                ],

            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $record = new Ccqs();
                $record->fill($request->all());
                $record->total_ccq_score = ($record->q1 + $record->q2 + $record->q3 + $record->q4 + $record->q5 + $record->q6 + $record->q7 + $record->q8 +$record->q9 +$record->q10) / 10;
                $record->symptom_score = ($record->q1 + $record->q2 + $record->q5 + $record->q6) / 4;
                $record->mental_state_score = ($record->q3 + $record->q4) / 2;
                $record->functional_state_score = ($record->q7 + $record->q8 +$record->q9 +$record->q10) / 4;

                $record->diagnose_date = $record->convertDate($request->input('diagnose_date'), 'Europe/Athens');
                $result['success'] = $record->save();
                $cats = Ccqs::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();
                if($result['success'])
                    return redirect()->route('ccqs.patients.list', ['patient'=>$patient->id,'items' => $cats])->with('success', 'Το ερωτηματολόγιο CCQ προστέθηκε επιτυχώς!');
                else
                    return redirect()->route('ccqs.patients.list', ['patient'=>$patient->id,'items' => $cats])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }

    public function edit(Request $request, Patients $patient, Ccqs $ccq)
    {

        if(Auth::check()) {
            $this->authorize('update',[Ccqs::class, $patient]);

            $ccq->is_for_field = true;
            $ccqsQuestion = CcqQuestion::query()->orderBy('id')->get();
            return view('admin.ccqs.edit',[
                'formAction' => 'edit',
                'data' => $ccq,
                'patientId'=>$patient->id,
                'patientName'=> $patient->account->first_name.' '.$patient->account->last_name,
                'questions' => $ccqsQuestion,]);
        }else{
            return redirect('login');
        }
    }

    public function update(Request $request, Patients $patient, Ccqs $ccq)
    {


        if(Auth::check()) {
            $this->authorize('update',[Ccqs::class, $patient]);

            $validator = Validator::make($request->all(),[
                'patient_id' => [
                    'required',
                    'int',
                    Rule::exists('patients', 'id')->whereNull('deleted_at'),
                ],
                'q1' => 'required|int|max:6, min:0',
                'q2' => 'required|int|max:6, min:0',
                'q3' => 'required|int|max:6, min:0',
                'q4' => 'required|int|max:6, min:0',
                'q5' => 'required|int|max:6, min:0',
                'q6' => 'required|int|max:6, min:0',
                'q7' => 'required|int|max:6, min:0',
                'q8' => 'required|int|max:6, min:0',
                'q9' => 'required|int|max:6, min:0',
                'q10' => 'required|int|max:6, min:0',
                'status' =>  [
                    'required',
                    'string',
                    Rule::in(['baseline','exacerbation']),
                ],

            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $ccq->fill($request->all());
                $ccq->total_ccq_score = ($ccq->q1 + $ccq->q2 + $ccq->q3 + $ccq->q4 + $ccq->q5 + $ccq->q6 + $ccq->q7 + $ccq->q8 +$ccq->q9 +$ccq->q10) / 10;
                $ccq->symptom_score = ($ccq->q1 + $ccq->q2 + $ccq->q5 + $ccq->q6) / 4;
                $ccq->mental_state_score = ($ccq->q3 + $ccq->q4) / 2;
                $ccq->functional_state_score = ($ccq->q7 + $ccq->q8 +$ccq->q9 +$ccq->q10) / 4;
                $result['success'] = $ccq->save();
                $ccqs = Ccqs::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();
                if($result['success'])
                    return redirect()->route('ccqs.patients.list', ['patient'=>$patient->id,'items' => $ccqs])->with('success', 'Το ερωτηματολόγιο CCQ ανανεώθηκε επιτυχώς!');
                else
                    return redirect()->route('ccqs.patients.list', ['patient'=>$patient->id,'items' => $ccqs])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }

    public function destroy(Request $request, Patients $patient, Ccqs $ccq)
    {
        $this->authorize('delete', [Ccqs::class, $patient]);
        $ccq->is_for_field = true;
        $catDate = $ccq->diagnose_date;

        $result['success'] = $ccq->delete();

        $ccqs = Ccqs::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();

        return redirect()->route('ccqs.patients.list', ['patient'=> $patient->id,'items' => $ccqs])->with('success', 'To CCQ για '.$catDate.' διαγράφηκε επιτυχώς!');

    }
}
