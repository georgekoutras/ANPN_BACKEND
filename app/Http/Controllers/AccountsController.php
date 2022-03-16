<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Deaths;
use App\Models\Patients;
use App\Rules\MatchOldPassword;
use App\Traits\FormatDates;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\isNull;

class AccountsController extends Controller
{
    use FormatDates;

    public function index(Request $request){

        if(Auth::check()) {
            $this->authorize('index',Accounts::class);

            if($request->user()->role == 'administrator') {
                $accounts = Accounts::query()->get();
            }else if ($request->user()->role == 'doctor') {
                $accounts = Accounts::query()->join('patients', 'patients.account_id', '=', 'accounts.id')
                    ->where('patients.doctor_id', '=', $request->user()->id)
                    ->select(['accounts.*'])->get();
            }
            return view('admin.users.index', ['items' => $accounts,'role'=> $request->user()->role]);
        }else{
            return redirect('login');
        }

    }

    public function create(Request $request,$role){
        if(Auth::check()) {
            $this->authorize('store',[Accounts::class]);

            if($role == 'administrator'){
                $title = 'Διαχειριστή';
            }else if($role == 'doctor'){
                $title = 'Ιατρού';
            }else{
                $title = 'Ασθενή';
            }
            return view('admin.users.create',['title' => $title,'role'=> $role, 'formAction' => 'create','loggedRole'=> $request->user()->role,'loggedId'=> $request->user()->id]);
        }else{
            return redirect('login');
        }
    }

    public function store(Request $request){

        if(Auth::check()) {
            $this->authorize('store',[Accounts::class]);

            $validator = Validator::make($request->all(),[
                'role' => [
                    'required',
                    'string',
                    Rule::in(['administrator', 'doctor', 'patient']),
                ],
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'second_name' => 'nullable|string|max:255',
                'email' => 'required|unique:accounts,email|email|max:255',
                'password' => 'required|string',
                'mobile' => 'required|string|max:255',
                'notification_enabled' => 'required_unless:role,administrator|boolean',
                'notification_mode' => [
                    'nullable',
                    'required_unless:role,administrator',
                    'string',
                    Rule::in(['sms', 'email', 'push']),
                ],
                'doctor_id' => [
                    'required_if:role,patient',
                    'integer',
                    Rule::exists('accounts', 'id')->where('role','doctor')->whereNull('deleted_at'),
                ],
                'social_id' => [
                    'required_if:role,patient',
                    'digits:11'
                ],
                'sex' => [
                    'required_if:role,patient',
                    'boolean'
                ],
                'birth_date' => [
                    'required_if:role,patient',
                    'date_format:d/m/y',
                ],
                'first_diagnose_date' => [
                    'required_if:role,patient',
                    'date_format:d/m/y',
                ],
                'address' => [
                    'required_if:role,patient',
                    'string',
                ],
                'land_line' => [
                    'nullable',
                    'required_if:role,patient',
                    'integer'
                ],
                'file_id' => [
                    'required_if:role,patient',
                    'string',
                    'unique:patients',
                ],
            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {

                $account = new Accounts();
                $account->fill($request->all());
                $hashedPassword = Hash::make($request->input('password'));
                $account->password = $hashedPassword;
                $result['success'] = $account->save();
                $accounts = Accounts::query()->get();

                if($account->isPatient()){
                    $patient = new Patients();
                    $patient->fill($request->all());
                    $patient->account_id = $account->id;
                    $patient->birth_date = $patient->convertDate($request->input('birth_date'), 'Europe/Athens');
                    $patient->first_diagnose_date = $patient->convertDate($request->input('first_diagnose_date'), 'Europe/Athens');

                    $result['success2'] = $patient->save();

                    if($result['success'] && $result['success2'])
                        return redirect()->route('accounts.index', ['items' => $accounts])->with('success', 'Ο ασθενής προστέθηκε επιτυχώς επιτυχώς!');
                    else
                        return redirect()->route('accounts.index', ['items' => $accounts])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
                }

                if($result['success'])
                    return redirect()->route('accounts.index', ['items' => $accounts])->with('success', 'Ο χρήστης προστέθηκε επιτυχώς επιτυχώς!');
                else
                    return redirect()->route('accounts.index', ['items' => $accounts])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);

            }


        }else{
            return redirect('login');
        }
    }

    public function edit(Request $request,Accounts $account){

        if(Auth::check()) {
            $this->authorize('update',[Accounts::class, $account]);

            if($account->id == $request->user()->id){
                $title = 'Προφίλ';
                if($account->isPatient()){
                    $data = $account->load(['patientInfo','patientInfo.doctor']);
                    $data->patientInfo->is_for_field = true;

                }else{
                    $data = $account;
                }
            }else if($account->isAdmin()){
                $title = 'Διαχειριστή';
                $data = $account;
            }else if($account->isDoctor()){
                $title = 'Ιατρού';
                $data = $account;
            }else{
                $title = 'Ασθενή';
                $data = $account->load(['patientInfo','patientInfo.doctor']);
                $data->patientInfo->is_for_field = true;
            }
            return view('admin.users.edit',[
                'title' => $title,
                'role'=> $account->role,
                'formAction' => 'edit',
                'data' => $data,
                'loggedRole'=> $request->user()->role,
                'loggedId'=> $request->user()->id,
                'accountId' => $account->id,
                'patientId'=>$request->user()->isPatient() ? $request->user()->patientInfo->id : null]);
        }else{
            return redirect('login');
        }
    }

    public function update(Request $request, Accounts $account){

        if(Auth::check()) {
            $this->authorize('update',[Accounts::class, $account]);

            $validator = Validator::make($request->all(),[
                'role' => [
                    'required',
                    'string',
                    Rule::in(['administrator', 'doctor', 'patient']),
                ],
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'second_name' => 'nullable|string|max:255',
                'mobile' => 'required|string|max:255',
                'notification_enabled' => 'required_unless:role,administrator|boolean',
                'notification_mode' => [
                    'nullable',
                    'required_unless:role,administrator',
                    'string',
                    Rule::in(['sms', 'email', 'push']),
                ],
                'doctor_id' => [
                    'required_if:role,patient',
                    'integer',
                    Rule::exists('accounts', 'id')->where('role','doctor')->whereNull('deleted_at'),
                ],
                'social_id' => [
                    'required_if:role,patient',
                    'digits:11'
                ],
                'sex' => [
                    'required_if:role,patient',
                    'boolean'
                ],
                'birth_date' => [
                    'required_if:role,patient',
                    'date_format:d/m/y',
                ],
                'first_diagnose_date' => [
                    'required_if:role,patient',
                    'date_format:d/m/y',
                ],
                'address' => [
                    'required_if:role,patient',
                    'string',
                ],
                'land_line' => [
                    'nullable',
                    'required_if:role,patient',
                    'integer'
                ],
            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {

                $account->fill($request->all());
                $result['success'] = $account->save();
                $accounts = Accounts::query()->get();

                if($account->isPatient()){
                    $patient = Patients::where('account_id','=',$account->id)->first();
                    if($patient) {
                        $patient->fill($request->all());
                        $patient->account_id = $account->id;
                        $patient->birth_date = $patient->convertDate($request->input('birth_date'), 'Europe/Athens');
                        $patient->first_diagnose_date = $patient->convertDate($request->input('first_diagnose_date'), 'Europe/Athens');

                        $result['success2'] = $patient->save();
                    }
                    if($result['success'] && $result['success2'])
                        if($request->user()->isPatient())
                            return redirect()->route('daily_reports.patients.reports',['patient'=>$request->user()->patientInfo->id,'patientId'=>$request->user()->patientInfo->id,'accountId'=>$request->user()->id])
                                ->with('success', 'Τα στοιχεία σας ανανεώθηκαν επιτυχώς!');
                        else
                            return redirect()->route('accounts.index', ['items' => $accounts])->with('success', 'Τα στοιχεία του ασθενή ανανεώθηκαν επιτυχώς!');
                    else
                        if($request->user()->isPatient())
                            return redirect()->route('daily_reports.patients.reports',['patient'=>$request->user()->patientInfo->id,'patientId'=>$request->user()->patientInfo->id,'accountId'=>$request->user()->id])
                                ->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
                        else
                            return redirect()->route('accounts.index', ['items' => $accounts])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);

                }

                if($result['success'])
                    return redirect()->route('accounts.index', ['items' => $accounts])->with('success', 'Τα στοιχεία του χρήστη ανανεώθηκαν επιτυχώς!');
                else
                    return redirect()->route('accounts.index', ['items' => $accounts])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);

            }


        }else{
            return redirect('login');
        }
    }

    public function changePassword(Request $request, Accounts $account){

        if(Auth::check()) {
            $this->authorize('update',[Accounts::class, $account]);

            $validator = Validator::make($request->all(),[
                'curr_password' => ['required', function ($attribute, $value, $fail) use ($account) {
                    if (!Hash::check($value, $account->password)) {
                        return $fail(__('Ο τρέχων κωδικός που πληκτρολογήσατε είναι λάθος.'));
                    }
                }],
                'password' => ['required'],
                'repeat_password' => ['same:password'],
            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $hashedPassword = Hash::make($request->input('password'));
                $account->password = $hashedPassword;
                $result['success'] = $account->save();

                if($result['success'])
                    return redirect()->back()->with('success','Ο κωδικός πρόσβασης ανανεώθηκε επιτυχώς');
                else
                    return redirect()->back()->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);

            }
        }
    }

    public function destroy(Accounts $account){
        $this->authorize('delete',[Accounts::class, $account]);

        $fullName = $account->first_name.' '.$account->last_name;
        $ispPatient = false;
        $patient = null;
        if($account->isPatient()){
            $ispPatient = true;
            $patient = $account->patientInfo();
        }
        if($account->isDoctor() && $account->patients()->count() > 0){
            $result['success'] = false;
            $accounts = Accounts::query()->get();
            return redirect()->route('accounts.index', ['items' => $accounts])->withErrors(['Ο χρήστης '.$fullName.' έχει ασθενείς. Παρακαλώ μεταφέρετε τους ασθενείς σε άλλον ιατρό!']);
        }
        $result['success'] = $account->delete();

        if($result['success'] && $ispPatient){
                if(!is_null($patient))
                    $patient->delete();
        }

        $accounts = Accounts::query()->get();

        return redirect()->route('accounts.index', ['items' => $accounts])->with('success', 'Ο χρήστης '.$fullName.' διαγράφηκε επιτυχώς!');
    }

    public function searchDoctors(Request $request){

        $doctors = Accounts::where('role','=','doctor')
            ->select(['id','first_name','last_name','second_name',DB::raw("concat(last_name,' ',first_name) as full_name")])
            ->where(function($query) use ($request) {
                $query->where('last_name', 'LIKE', "%".$request->input('search').'%')
                    ->orWhere('first_name', 'LIKE', "%".$request->input('search').'%')
                    ->orWhere('second_name', 'LIKE', "%".$request->input('search').'%');
                    })
            ->get();


        return response()->json($doctors);

    }

    public function searchPatients(Request $request){

        if($request->user()->role() == 'administrator') {
            $patients = Accounts::query()
                ->join('patients','patients.account_id','=','accounts.id')
                ->whereNotIn('patients.id', Deaths::whereNull('deleted_at')->pluck('patient_id')->toArray())
                ->select(['patients.id', 'accounts.first_name', 'accounts.last_name', 'accounts.second_name', DB::raw("concat(accounts.last_name,' ',accounts.first_name) as full_name")])
                ->where(function ($query) use ($request) {
                    $query->where('accounts.last_name', 'LIKE', "%" . $request->input('search') . '%')
                        ->orWhere('accounts.first_name', 'LIKE', "%" . $request->input('search') . '%')
                        ->orWhere('accounts.second_name', 'LIKE', "%" . $request->input('search') . '%');
                })
                ->get();
        }else if($request->user()->role() == 'doctor') {
            $patients = Accounts::query()
                ->join('patients','patients.account_id','=','accounts.id')
                ->whereNotIn('patients.id', Deaths::whereNull('deleted_at')->pluck('patient_id')->toArray())
                ->where('patients.doctor_id','=',$request->user()->id)
                ->select(['patients.id', 'accounts.first_name', 'accounts.last_name', 'accounts.second_name', DB::raw("concat(accounts.last_name,' ',accounts.first_name) as full_name")])
                ->where(function ($query) use ($request) {
                    $query->where('accounts.last_name', 'LIKE', "%" . $request->input('search') . '%')
                        ->orWhere('accounts.first_name', 'LIKE', "%" . $request->input('search') . '%')
                        ->orWhere('accounts.second_name', 'LIKE', "%" . $request->input('search') . '%');
                })
                ->get();
        }

        return response()->json($patients);

    }
}
