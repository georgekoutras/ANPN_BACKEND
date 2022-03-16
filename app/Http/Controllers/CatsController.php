<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\CatQuestion;
use App\Models\Cats;
use App\Models\Patients;
use App\Traits\FormatDates;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CatsController extends Controller
{
    use FormatDates;

    public function index(Request $request)
    {
        if(Auth::check()) {
            $this->authorize('index',[Cats::class]);

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
            return view('admin.cats.index', ['items' => $accounts->get()]);
        }else{
            return redirect('login');
        }

    }

    public function patientCats(Patients $patient){
        if(Auth::check()) {
            $this->authorize('patientCats', [Cats::class, $patient]);

            $cats = Cats::where('patient_id','=',$patient->id)->orderBy('diagnose_Date','desc')->get();
            foreach ($cats as $dr){
                $dr->is_for_field = true;
            }

            return view('admin.cats.patient_cats', [
                'items' => $cats,
                'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
                'patiendId'=>$patient->id]);
        }else{
            return redirect('login');
        }
    }

    public function create(Patients $patient){
        if(Auth::check()) {
            $this->authorize('patientCats', [Cats::class, $patient]);

            $catsQuestion = CatQuestion::query()->orderBy('label')->get();
            return view('admin.cats.create',[
                'formAction' => 'create',
                'questions' => $catsQuestion,
                'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
                'patientId'=>$patient->id,]);
        }else{
            return redirect('login');
        }
    }

    public function store(Request $request, Patients $patient)
    {
        if(Auth::check()) {
            $this->authorize('store', [Cats::class, $patient]);

            $validator = Validator::make($request->all(),[
                'patient_id' => [
                    'required',
                    'int',
                    Rule::exists('patients', 'id')->whereNull('deleted_at'),
                ],
                'q1' => 'required|int|max:5, min:0',
                'q2' => 'required|int|max:5, min:0',
                'q3' => 'required|int|max:5, min:0',
                'q4' => 'required|int|max:5, min:0',
                'q5' => 'required|int|max:5, min:0',
                'q6' => 'required|int|max:5, min:0',
                'q7' => 'required|int|max:5, min:0',
                'q8' => 'required|int|max:5, min:0',
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
                $record = new Cats();
                $record->fill($request->all());
                $record->total_cat_scale = $record->q1 + $record->q2 + $record->q3 + $record->q4 + $record->q5 + $record->q6 + $record->q7 + $record->q8;
                $record->diagnose_date = $record->convertDate($request->input('diagnose_date'), 'Europe/Athens');
                $result['success'] = $record->save();
                $cats = Cats::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();
                if($result['success'])
                    return redirect()->route('cats.patients.list', ['patient'=>$patient->id,'items' => $cats])->with('success', 'Το ερωτηματολόγιο CAT προστέθηκε επιτυχώς!');
                else
                    return redirect()->route('cats.patients.list', ['patient'=>$patient->id,'items' => $cats])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }


    public function edit(Request $request, Patients $patient, Cats $cat)
    {

        if(Auth::check()) {
            $this->authorize('update',[Cats::class, $patient]);

            $cat->is_for_field = true;
            $catsQuestion = CatQuestion::query()->orderBy('label')->get();
            return view('admin.cats.edit',[
                'formAction' => 'edit',
                'data' => $cat,
                'patientId'=>$patient->id,
                'patientName'=> $patient->account->first_name.' '.$patient->account->last_name,
                'questions' => $catsQuestion,]);
        }else{
            return redirect('login');
        }
    }


    public function update(Request $request, Patients $patient, Cats $cat)
    {


        if(Auth::check()) {
            $this->authorize('update',[Cats::class, $patient]);

            $validator = Validator::make($request->all(),[
                'patient_id' => [
                    'required',
                    'int',
                    Rule::exists('patients', 'id')->whereNull('deleted_at'),
                ],
                'q1' => 'required|int|max:5, min:0',
                'q2' => 'required|int|max:5, min:0',
                'q3' => 'required|int|max:5, min:0',
                'q4' => 'required|int|max:5, min:0',
                'q5' => 'required|int|max:5, min:0',
                'q6' => 'required|int|max:5, min:0',
                'q7' => 'required|int|max:5, min:0',
                'q8' => 'required|int|max:5, min:0',
                'status' =>  [
                    'required',
                    'string',
                    Rule::in(['baseline','exacerbation']),
                ],

            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $cat->fill($request->all());
                $cat->total_cat_scale = $cat->q1 + $cat->q2 + $cat->q3 + $cat->q4 + $cat->q5 + $cat->q6 + $cat->q7 + $cat->q8;
                $result['success'] = $cat->save();
                $cats = Cats::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();
                if($result['success'])
                    return redirect()->route('cats.patients.list', ['patient'=>$patient->id,'items' => $cats])->with('success', 'Το ερωτηματολόγιο CAT ανανεώθηκε επιτυχώς!');
                else
                    return redirect()->route('cats.patients.list', ['patient'=>$patient->id,'items' => $cats])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }

    public function destroy(Request $request, Patients $patient, Cats $cat)
    {
        $this->authorize('delete', [Cats::class, $patient]);
        $cat->is_for_field = true;
        $catDate = $cat->diagnose_date;

        $result['success'] = $cat->delete();

        $cats = Cats::where('patient_id','=',$patient->id)->orderBy('created_at','desc')->get();

        return redirect()->route('cats.patients.list', ['patient'=> $patient->id,'items' => $cats])->with('success', 'To CAT για '.$catDate.' διαγράφηκε επιτυχώς!');

    }
}
