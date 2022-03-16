<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Patients;
use App\Models\Reading;
use App\Models\Treatments;
use App\Traits\FormatDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TreatmentController extends Controller
{
    use FormatDates;

    public function index(Request $request)
    {
        if(Auth::check()) {
            $this->authorize('index',[Treatments::class]);

            if($request->user()->role == 'administrator') {
                $accounts = Accounts::query()->join('patients', 'patients.account_id', '=', 'accounts.id');
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
            return view('admin.treatments.index', ['items' => $accounts->get()]);
        }else{
            return redirect('login');
        }

    }

    public function patientTreatments(Patients $patient){
        if(Auth::check()) {
            $this->authorize('patientTreatments', [Treatments::class, $patient]);

            $treatments = Treatments::where('patient_id','=',$patient->id)->orderBy('diagnose_Date','desc')->get();
            foreach ($treatments as $dr){
                $dr->is_for_field = true;
            }

            return view('admin.treatments.patient_treatments', [
                'items' => $treatments,
                'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
                'patiendId'=>$patient->id]);
        }else{
            return redirect('login');
        }
    }

    public function create(Patients $patient){
        if(Auth::check()) {
            $this->authorize('store', [Reading::class, $patient]);

            return view('admin.treatments.create',[
                'formAction' => 'create',
                'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
                'patientId'=>$patient->id,]);
        }else{
            return redirect('login');
        }
    }

    public function store(Request $request, Patients $patient)
    {
        if(Auth::check()) {
            $this->authorize('store', [Treatments::class, $patient]);

            $validator = Validator::make($request->all(),[
                'patient_id' => [
                    'required',
                    'int',
                    Rule::exists('patients', 'id')->whereNull('deleted_at'),
                ],
                'diagnose_date' => [
                    'required_if:role,patient',
                    'date_format:d/m/y',
                ],
                'status' =>  [
                    'required',
                    'string',
                    Rule::in(['baseline','exacerbation']),
                ],
                'ltot_start_date' => [
                    'nullable',
                    'date_format:d/m/y',
                ],
                'ltot_device' =>  [
                    'nullable',
                    'string',
                    Rule::in(['none','Liquid','Cylinder','Concentrator']),
                ],
                'ventilation_start_date' => [
                    'nullable',
                    'date_format:d/m/y',
                ],
                'ventilation_device' =>  [
                    'nullable',
                    'string',
                    Rule::in(['none','BiPAP','CPAP']),
                ],
                'ltot' => 'nullable|int|max:1, min:0',
                'antibiotics' => 'nullable|int|max:1, min:0',
                'antiflu' => 'nullable|int|max:1, min:0',
                'antipneum' => 'nullable|int|max:1, min:0',
                'lama' => 'nullable|int|max:1, min:0',
                'long_acting_b2' => 'nullable|int|max:1, min:0',
                'mycolytocis' => 'nullable|int|max:1, min:0',
                'niv' => 'nullable|int|max:1, min:0',
                'pdef4_inhalator' => 'nullable|int|max:1, min:0',
                'sama' => 'nullable|int|max:1, min:0',
                'short_acting_b2' => 'nullable|int|max:1, min:0',
                'steroids_inhaled' => 'nullable|int|max:1, min:0',
                'steroids_oral' => 'nullable|int|max:1, min:0',
                'theophyline' => 'nullable|int|max:1, min:0',
                'ultra_long_acting_b2' => 'nullable|int|max:1, min:0',
                'notes'=>'nullable|string|max:12000'

            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $record = new Treatments();
                $record->fill($request->all());
                $record->diagnose_date = $record->convertDate($request->input('diagnose_date'), 'Europe/Athens');
                if($request->has('ltot_start_date') && !is_null($request->input('ltot_start_date')))
                    $record->ltot_start_date = $record->convertDate($request->input('ltot_start_date'), 'Europe/Athens');
                if($request->has('ventilation_start_date') && !is_null($request->input('ventilation_start_date')))
                    $record->ventilation_start_date = $record->convertDate($request->input('ventilation_start_date'), 'Europe/Athens');

                $result['success'] = $record->save();
                $treatments = Treatments::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();
                if($result['success'])
                    return redirect()->route('treatments.patients.list', ['patient'=>$patient->id,'items' => $treatments])->with('success', 'Η θεραπεία προστέθηκε επιτυχώς!');
                else
                    return redirect()->route('treatments.patients.list', ['patient'=>$patient->id,'items' => $treatments])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }


    public function edit(Request $request, Patients $patient, Treatments $treatment)
    {

        if(Auth::check()) {
            $this->authorize('update',[Treatments::class, $patient]);

            $treatment->is_for_field = true;
            return view('admin.treatments.edit',[
                'formAction' => 'edit',
                'data' => $treatment,
                'patientId'=>$patient->id,
                'patientName'=> $patient->account->first_name.' '.$patient->account->last_name,]);
        }else{
            return redirect('login');
        }
    }

    public function update(Request $request, Patients $patient, Treatments $treatment)
    {


        if(Auth::check()) {
            $this->authorize('update',[Treatments::class, $patient]);

            $validator = Validator::make($request->all(),[
                'patient_id' => [
                    'required',
                    'int',
                    Rule::exists('patients', 'id')->whereNull('deleted_at'),
                ],
                'diagnose_date' => [
                    'required_if:role,patient',
                    'date_format:d/m/y',
                ],
                'status' =>  [
                    'required',
                    'string',
                    Rule::in(['baseline','exacerbation']),
                ],
                'ltot_start_date' => [
                    'nullable',
                    'date_format:d/m/y',
                ],
                'ltot_device' =>  [
                    'nullable',
                    'string',
                    Rule::in(['none','Liquid','Cylinder','Concentrator']),
                ],
                'ventilation_start_date' => [
                    'nullable',
                    'date_format:d/m/y',
                ],
                'ventilation_device' =>  [
                    'nullable',
                    'string',
                    Rule::in(['none','BiPAP','CPAP']),
                ],
                'ltot' => 'nullable|int|max:1, min:0',
                'antibiotics' => 'nullable|int|max:1, min:0',
                'antiflu' => 'nullable|int|max:1, min:0',
                'antipneum' => 'nullable|int|max:1, min:0',
                'lama' => 'nullable|int|max:1, min:0',
                'long_acting_b2' => 'nullable|int|max:1, min:0',
                'mycolytocis' => 'nullable|int|max:1, min:0',
                'niv' => 'nullable|int|max:1, min:0',
                'pdef4_inhalator' => 'nullable|int|max:1, min:0',
                'sama' => 'nullable|int|max:1, min:0',
                'short_acting_b2' => 'nullable|int|max:1, min:0',
                'steroids_inhaled' => 'nullable|int|max:1, min:0',
                'steroids_oral' => 'nullable|int|max:1, min:0',
                'theophyline' => 'nullable|int|max:1, min:0',
                'ultra_long_acting_b2' => 'nullable|int|max:1, min:0',
                'notes'=>'nullable|string|max:12000'

            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $treatment->fill($request->all());
                if($request->has('ltot_start_date') && !is_null($request->input('ltot_start_date')))
                    $treatment->ltot_start_date = $treatment->convertDate($request->input('ltot_start_date'), 'Europe/Athens');
                if($request->has('ventilation_start_date') && !is_null($request->input('ventilation_start_date')))
                    $treatment->ventilation_start_date = $treatment->convertDate($request->input('ventilation_start_date'), 'Europe/Athens');

                $result['success'] = $treatment->save();
                $treatments = Treatments::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();
                if($result['success'])
                    return redirect()->route('treatments.patients.list', ['patient'=>$patient->id,'items' => $treatments])->with('success', 'Η θεραπεία ανανεώθηκε επιτυχώς!');
                else
                    return redirect()->route('treatments.patients.list', ['patient'=>$patient->id,'items' => $treatments])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }

    public function destroy(Request $request, Patients $patient, Treatments $treatment)
    {
        $this->authorize('delete', [Reading::class, $patient]);
        $treatment->is_for_field = true;
        $catDate = $treatment->diagnose_date;

        $result['success'] = $treatment->delete();

        $treatments = Treatments::where('patient_id','=',$patient->id)->orderBy('created_at','desc')->get();

        return redirect()->route('treatments.patients.list', ['patient'=> $patient->id,'items' => $treatments])->with('success', 'Η θεραπεία για '.$catDate.' διαγράφηκε επιτυχώς!');

    }
}
