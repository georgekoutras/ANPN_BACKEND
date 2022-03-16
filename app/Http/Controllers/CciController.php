<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\CatQuestion;
use App\Models\Cats;
use App\Models\Ccis;
use App\Models\Patients;
use App\Traits\FormatDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CciController extends Controller
{
    use FormatDates;

    public function index(Request $request)
    {
        if(Auth::check()) {
            $this->authorize('index',[Ccis::class]);

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
            return view('admin.charlsons.index', ['items' => $accounts->get()]);
        }else{
            return redirect('login');
        }

    }

    public function patientCcis(Patients $patient){
        if(Auth::check()) {
            $this->authorize('patientCcis', [Ccis::class, $patient]);

            $ccis = Ccis::where('patient_id','=',$patient->id)->orderBy('diagnose_Date','desc')->get();
            foreach ($ccis as $dr){
                $dr->is_for_field = true;
            }

            return view('admin.charlsons.patient_charlsons', [
                'items' => $ccis,
                'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
                'patiendId'=>$patient->id]);
        }else{
            return redirect('login');
        }
    }

    public function create(Patients $patient){
        if(Auth::check()) {
            $this->authorize('patientCcis', [Ccis::class, $patient]);

            return view('admin.charlsons.create',[
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
            $this->authorize('store', [Ccis::class, $patient]);

            $validator = Validator::make($request->all(),[
                'patient_id' => [
                    'required',
                    'int',
                    Rule::exists('patients', 'id')->whereNull('deleted_at'),
                ],
                'myocardialInfarction' => 'required|int|max:1, min:0',
                'congestiveHeartFailure' => 'required|int|max:1, min:0',
                'peripheralVascularDisease' => 'required|int|max:1, min:0',
                'cerebrovascularDisease' => 'required|int|max:1, min:0',
                'dementia' => 'required|int|max:1, min:0',
                'chronicPulmonaryDisease' => 'required|int|max:1, min:0',
                'connectiveTissueDisease' => 'required|int|max:1, min:0',
                'ulcerDisease' => 'required|int|max:1, min:0',
                'liverDiseaseMild' => 'required|int|max:1, min:0',
                'diabetes' => 'required|int|max:1, min:0',
                'hemiplegia' => 'required|int|max:1, min:0',
                'renalDiseaseModerateOrSevere' => 'required|int|max:1, min:0',
                'diabetesWithEndOrganDamage' => 'required|int|max:1, min:0',
                'anyTumor' => 'required|int|max:1, min:0',
                'leukemia' => 'required|int|max:1, min:0',
                'malignantLymphoma' => 'required|int|max:1, min:0',
                'liverDiseaseModerateOrSevere' => 'required|int|max:1, min:0',
                'metastaticSolidMalignancy' => 'required|int|max:1, min:0',
                'aids' => 'required|int|max:1, min:0',
                'noConditionAvailable' => 'required|int|max:1, min:0',
                'diagnose_date' => [
                    'required_if:role,patient',
                    'date_format:d/m/y',
                ],

            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $record = new Ccis();
                $record->fill($request->all());
                $record->totalCharlson = $this->calculateTotalCharlson($request);
                $record->diagnose_date = $record->convertDate($request->input('diagnose_date'), 'Europe/Athens');
                $result['success'] = $record->save();
                $ccis = Ccis::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();
                if($result['success'])
                    return redirect()->route('ccis.patients.list', ['patient'=>$patient->id,'items' => $ccis])->with('success', 'Το ερωτηματολόγιο Charlson προστέθηκε επιτυχώς!');
                else
                    return redirect()->route('ccis.patients.list', ['patient'=>$patient->id,'items' => $ccis])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }

    private function calculateTotalCharlson(Request $request){
        $sum = 0;

		if ($request->input('myocardialInfarction') > 0)
            $sum += 1;

		if ($request->input('congestiveHeartFailure') > 0)
            $sum += 1;

		if ($request->input('peripheralVascularDisease')> 0)
            $sum += 1;

		if ($request->input('cerebrovascularDisease') > 0)
            $sum += 1;

		if ($request->input('dementia') > 0)
            $sum += 1;

		if ($request->input('chronicPulmonaryDisease') > 0)
            $sum += 1;
;
		if ($request->input('connectiveTissueDisease') > 0)
            $sum += 1;

		if ($request->input('ulcerDisease') > 0)
            $sum += 1;

		if ($request->input('liverDiseaseMild') > 0)
            $sum += 1;

		if ($request->input('diabetes') > 0)
            $sum += 1;

		if ($request->input('hemiplegia') > 0)
            $sum += 2;

		if ($request->input('renalDiseaseModerateOrSevere') > 0)
            $sum += 2;

		if ($request->input('diabetesWithEndOrganDamage') > 0)
            $sum += 2;

		if ($request->input('anyTumor') > 0)
            $sum += 2;

		if ($request->input('leukemia') > 0)
            $sum += 2;

		if ($request->input('malignantLymphoma') > 0)
            $sum += 2;

		if ($request->input('liverDiseaseModerateOrSevere') > 0)
            $sum += 3;

		if ($request->input('metastaticSolidMalignancy') > 0)
            $sum += 6;

		if ($request->input('aids') > 0)
            $sum += 6;

	    return $sum;
    }

    public function edit(Request $request, Patients $patient, Ccis $cci)
    {

        if(Auth::check()) {
            $this->authorize('update',[Ccis::class, $patient]);

            $cci->is_for_field = true;
            return view('admin.charlsons.edit',[
                'formAction' => 'edit',
                'data' => $cci,
                'patientId'=>$patient->id,
                'patientName'=> $patient->account->first_name.' '.$patient->account->last_name,]);
        }else{
            return redirect('login');
        }
    }


    public function update(Request $request, Patients $patient, Ccis $cci)
    {


        if(Auth::check()) {
            $this->authorize('update',[Ccis::class, $patient]);

            $validator = Validator::make($request->all(),[
                'patient_id' => [
                    'required',
                    'int',
                    Rule::exists('patients', 'id')->whereNull('deleted_at'),
                ],
                'myocardialInfarction' => 'required|int|max:1, min:0',
                'congestiveHeartFailure' => 'required|int|max:1, min:0',
                'peripheralVascularDisease' => 'required|int|max:1, min:0',
                'cerebrovascularDisease' => 'required|int|max:1, min:0',
                'dementia' => 'required|int|max:1, min:0',
                'chronicPulmonaryDisease' => 'required|int|max:1, min:0',
                'connectiveTissueDisease' => 'required|int|max:1, min:0',
                'ulcerDisease' => 'required|int|max:1, min:0',
                'liverDiseaseMild' => 'required|int|max:1, min:0',
                'diabetes' => 'required|int|max:1, min:0',
                'hemiplegia' => 'required|int|max:1, min:0',
                'renalDiseaseModerateOrSevere' => 'required|int|max:1, min:0',
                'diabetesWithEndOrganDamage' => 'required|int|max:1, min:0',
                'anyTumor' => 'required|int|max:1, min:0',
                'leukemia' => 'required|int|max:1, min:0',
                'malignantLymphoma' => 'required|int|max:1, min:0',
                'liverDiseaseModerateOrSevere' => 'required|int|max:1, min:0',
                'metastaticSolidMalignancy' => 'required|int|max:1, min:0',
                'aids' => 'required|int|max:1, min:0',
                'noConditionAvailable' => 'required|int|max:1, min:0',
                'diagnose_date' => [
                    'required_if:role,patient',
                    'date_format:d/m/y',
                ],

            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $cci->fill($request->all());
                $cci->totalCharlson =$this->calculateTotalCharlson($request);;
                $result['success'] = $cci->save();
                $ccis = Ccis::where('patient_id','=',$patient->id)->orderBy('diagnose_date','desc')->get();
                if($result['success'])
                    return redirect()->route('ccis.patients.list', ['patient'=>$patient->id,'items' => $ccis])->with('success', 'Το ερωτηματολόγιο Charlson ανανεώθηκε επιτυχώς!');
                else
                    return redirect()->route('ccis.patients.list', ['patient'=>$patient->id,'items' => $ccis])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }

    public function destroy(Request $request, Patients $patient, Ccis $cci)
    {
        $this->authorize('delete', [Ccis::class, $patient]);
        $cci->is_for_field = true;
        $catDate = $cci->diagnose_date;

        $result['success'] = $cci->delete();

        $ccis = Ccis::where('patient_id','=',$patient->id)->orderBy('created_at','desc')->get();

        return redirect()->route('ccis.patients.list', ['patient'=> $patient->id,'items' => $ccis])->with('success', 'To Charlson για '.$catDate.' διαγράφηκε επιτυχώς!');

    }
}
