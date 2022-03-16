<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Ccis;
use App\Models\Patients;
use App\Models\Reading;
use App\Traits\FormatDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReadingController extends Controller
{
    use FormatDates;

    public function index(Request $request)
    {
        if(Auth::check()) {
            $this->authorize('index',[Reading::class]);

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
            return view('admin.readings.index', ['items' => $accounts->get()]);
        }else{
            return redirect('login');
        }

    }

    public function patientreadings(Patients $patient){
        if(Auth::check()) {
            $this->authorize('patientReadings', [Reading::class, $patient]);

            $readings = Reading::where('patient_id','=',$patient->id)->orderBy('diagnose_Date','desc')->get();
            foreach ($readings as $dr){
                $dr->is_for_field = true;
            }

            return view('admin.readings.patient_readings', [
                'items' => $readings,
                'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
                'patiendId'=>$patient->id]);
        }else{
            return redirect('login');
        }
    }

    public function create(Patients $patient){
        if(Auth::check()) {
            $this->authorize('patientReadings', [Reading::class, $patient]);

            return view('admin.readings.create',[
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
            $this->authorize('store', [Reading::class, $patient]);

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
                'weight' => 'nullable|int|min:0',
                'height' => 'nullable|int|min:0',
                'pxy' => 'nullable|int|min:0',
                'mmrc' => 'nullable|int|max:4, min:0',
                'smoker' => 'nullable|int|max:2, min:0',
                'notes' => 'nullable|string',
                'fev1' => 'nullable|numeric|min:0',
                'fev1_pro' => 'nullable|numeric|min:0',
                'fvc' => 'nullable|numeric|min:0',
                'fvc_pro' => 'nullable|numeric|min:0',
                'fev1_fvc' => 'nullable|numeric|min:0',
                'rv' => 'nullable|numeric|min:0',
                'rv_pro' => 'nullable|numeric|min:0',
                'tlc' => 'nullable|numeric|min:0',
                'tlc_pro' => 'nullable|numeric|min:0',
                'rv_tlc' => 'nullable|numeric|min:0',
                'satO2_pro' => 'nullable|numeric|min:0',
                'dlco_pro' => 'nullable|numeric|min:0',
                'pao2' => 'nullable|numeric|min:0',
                'paco2' => 'nullable|numeric|min:0',
                'hco3' => 'nullable|numeric|min:0',
                'pH' => 'nullable|numeric|min:0',
                'fvc_pre' => 'nullable|numeric|min:0',
                'fvc_pre_pro' => 'nullable|numeric|min:0',
                'fev1_pre' => 'nullable|numeric|min:0',
                'fev1_pre_pro' => 'nullable|numeric|min:0',
                'fev1_fvc_pre' => 'nullable|numeric|min:0',
                'fef25_75_pre_pro' => 'nullable|numeric|min:0',
                'pef_pre_pro' => 'nullable|numeric|min:0',
                'tlc_pre' => 'nullable|numeric|min:0',
                'tlc_pre_pro' => 'nullable|numeric|min:0',
                'frc_pre' => 'nullable|numeric|min:0',
                'frc_pre_pro' => 'nullable|numeric|min:0',
                'rv_pre' => 'nullable|numeric|min:0',
                'rv_pre_pro' => 'nullable|numeric|min:0',
                'kco_pro' => 'nullable|numeric|min:0',
                'hematocrit' => 'nullable|numeric|min:0',
                'fvc_post' => 'nullable|numeric|min:0',
                'del_fvc_pro' => 'nullable|numeric|min:0',
                'fev1_post' => 'nullable|numeric|min:0',
                'del_fev1_post' => 'nullable|numeric|min:0',
                'del_fef25_75_pro' => 'nullable|numeric|min:0',
                'del_pef_pro' => 'nullable|numeric|min:0',
            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $record = new Reading();
                $record->fill($request->all());
                $record->diagnose_date = $record->convertDate($request->input('diagnose_date'), 'Europe/Athens');
                $result['success'] = $record->save();
                $readings = Reading::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();
                if($result['success'])
                    return redirect()->route('readings.patients.list', ['patient'=>$patient->id,'items' => $readings])->with('success', 'Η διάγνωση προστέθηκε επιτυχώς!');
                else
                    return redirect()->route('readings.patients.list', ['patient'=>$patient->id,'items' => $readings])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }

    public function edit(Request $request, Patients $patient, Reading $reading)
    {

        if(Auth::check()) {
            $this->authorize('update',[Reading::class, $patient]);

            $reading->is_for_field = true;
            return view('admin.readings.edit',[
                'formAction' => 'edit',
                'data' => $reading,
                'patientId'=>$patient->id,
                'patientName'=> $patient->account->first_name.' '.$patient->account->last_name,]);
        }else{
            return redirect('login');
        }
    }

    public function update(Request $request, Patients $patient, Reading $reading)
    {


        if(Auth::check()) {
            $this->authorize('update',[Reading::class, $patient]);

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
                'weight' => 'nullable|int|min:0',
                'height' => 'nullable|int|min:0',
                'pxy' => 'nullable|int|min:0',
                'mmrc' => 'nullable|int|max:4, min:0',
                'smoker' => 'nullable|int|max:2, min:0',
                'notes' => 'nullable|string',
                'fev1' => 'nullable|numeric|min:0',
                'fev1_pro' => 'nullable|numeric|min:0',
                'fvc' => 'nullable|numeric|min:0',
                'fvc_pro' => 'nullable|numeric|min:0',
                'fev1_fvc' => 'nullable|numeric|min:0',
                'rv' => 'nullable|numeric|min:0',
                'rv_pro' => 'nullable|numeric|min:0',
                'tlc' => 'nullable|numeric|min:0',
                'tlc_pro' => 'nullable|numeric|min:0',
                'rv_tlc' => 'nullable|numeric|min:0',
                'satO2_pro' => 'nullable|numeric|min:0',
                'dlco_pro' => 'nullable|numeric|min:0',
                'pao2' => 'nullable|numeric|min:0',
                'paco2' => 'nullable|numeric|min:0',
                'hco3' => 'nullable|numeric|min:0',
                'pH' => 'nullable|numeric|min:0',
                'fvc_pre' => 'nullable|numeric|min:0',
                'fvc_pre_pro' => 'nullable|numeric|min:0',
                'fev1_pre' => 'nullable|numeric|min:0',
                'fev1_pre_pro' => 'nullable|numeric|min:0',
                'fev1_fvc_pre' => 'nullable|numeric|min:0',
                'fef25_75_pre_pro' => 'nullable|numeric|min:0',
                'pef_pre_pro' => 'nullable|numeric|min:0',
                'tlc_pre' => 'nullable|numeric|min:0',
                'tlc_pre_pro' => 'nullable|numeric|min:0',
                'frc_pre' => 'nullable|numeric|min:0',
                'frc_pre_pro' => 'nullable|numeric|min:0',
                'rv_pre' => 'nullable|numeric|min:0',
                'rv_pre_pro' => 'nullable|numeric|min:0',
                'kco_pro' => 'nullable|numeric|min:0',
                'hematocrit' => 'nullable|numeric|min:0',
                'fvc_post' => 'nullable|numeric|min:0',
                'del_fvc_pro' => 'nullable|numeric|min:0',
                'fev1_post' => 'nullable|numeric|min:0',
                'del_fev1_post' => 'nullable|numeric|min:0',
                'del_fef25_75_pro' => 'nullable|numeric|min:0',
                'del_pef_pro' => 'nullable|numeric|min:0',
            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $reading->fill($request->all());
                $result['success'] = $reading->save();
                $readings = Reading::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();
                if($result['success'])
                    return redirect()->route('readings.patients.list', ['patient'=>$patient->id,'items' => $readings])->with('success', 'Η διάγνωση ανανεώθηκε επιτυχώς!');
                else
                    return redirect()->route('readings.patients.list', ['patient'=>$patient->id,'items' => $readings])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }

    public function destroy(Request $request, Patients $patient, Reading $reading)
    {
        $this->authorize('delete', [Reading::class, $patient]);
        $reading->is_for_field = true;
        $catDate = $reading->diagnose_date;

        $result['success'] = $reading->delete();

        $readings = Reading::where('patient_id','=',$patient->id)->orderBy('created_at','desc')->get();

        return redirect()->route('readings.patients.list', ['patient'=> $patient->id,'items' => $readings])->with('success', 'Η διάγνωση για '.$catDate.' διαγράφηκε επιτυχώς!');

    }
}
