<?php

namespace App\Http\Controllers;

use App\Core\Base\ModelWithTimestamps;
use App\Mail\NotificationMail;
use App\Mail\Rm\TaskListMail;
use App\Models\AccountDeviceToken;
use App\Models\Accounts;
use App\Models\Core\Language;
use App\Models\Core\User;
use App\Models\DailyReport;
use App\Models\DailyReportQuestion;
use App\Models\Notifications;
use App\Models\Patients;
use App\Traits\FormatDates;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DailyReportController extends Controller
{

    use FormatDates;

    public function index(Request $request)
    {
        if(Auth::check()) {
            $this->authorize('index',DailyReport::class);

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
            return view('admin.daily_reports.index', ['items' => $accounts->get()]);
        }else{
            return redirect('login');
        }

    }

    public function create(Request $request,Patients $patient){
        if(Auth::check()) {
            $this->authorize('allReports', [DailyReport::class, $patient]);

            $dailyReportQuestions = DailyReportQuestion::query()->orderBy('label')->get();
            return view('admin.daily_reports.create',[
                'formAction' => 'create',
                'questions' => $dailyReportQuestions,
                'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
                'patientId'=>$patient->id,
                'my_reports' => $request->user()->isPatient() && $request->user()->patientInfo->id == $patient->id,
                'accountId' => $request->user()->id,
            ]);

        }else{
            return redirect('login');
        }
    }

    public function store(Request $request, Patients $patient){
        if(Auth::check()) {
            $this->authorize('store', [DailyReport::class, $patient]);

            $validator = Validator::make($request->all(),[
                'patient_id' => [
                    'required',
                    'int',
                    Rule::exists('patients', 'id')->whereNull('deleted_at'),
                ],
                'q1' => 'required|int|max:1, min:0',
                'q2' => 'required|int|max:1, min:0',
                'q3' => 'required|int|max:1, min:0',
                'q4' => 'required|int|max:1, min:0',
                'q5' => 'required|int|max:1, min:0',
                'q1a' => 'required_if:q1,1|int|max:1, min:0',
                'q1b' => 'required_if:q1,1|int|max:1, min:0',
                'q1c' => 'required_if:q1,1|int|max:1, min:0',
                'q3a' => 'required_if:q3,1|int|max:1, min:0',
                'q3b' => 'required_if:q3,1|int|max:1, min:0',
                'q3c' => 'required_if:q3,1|int|max:1, min:0',
                'walkingDist' => 'nullable|numeric|min:0',
                'heartRate' => 'nullable|numeric|min:0',
                'sat02' => 'nullable|numeric|min:0,max:100',
                'pefr' => 'nullable|numeric|min:0',
                'temperature' => 'nullable|numeric|min:0',
            ]);
            if ($validator->fails()){
                throw new ValidationException($validator);
            }else {
                $record = new DailyReport();
                $record->fill($request->all());
                $result['success'] = $record->save();
                $this->createNotification($record);
                $dailyReports = DailyReport::where('patient_id','=',$patient->id)->orderBy('created_at','desc')->get();
                if($result['success'])
                    return redirect()->route('daily_reports.patients.reports', ['patient'=>$patient->id,'items' => $dailyReports])->with('success', 'Η ημερίσια αναφορά προστέθηκε επιτυχώς!');
                else
                    return redirect()->route('daily_reports.patients.reports', ['patient'=>$patient->id,'items' => $dailyReports])->withErrors([ 'Ουπς! Κάτι πήγε λάθος!']);
            }
        }else{
            return redirect('login');
        }
    }

    public function patientReports(Request $request,Patients $patient){
        if(Auth::check()) {
            $this->authorize('allReports', [DailyReport::class, $patient]);

            $dailyReports = DailyReport::where('patient_id','=',$patient->id)->orderBy('created_at','desc')->get();
            $today = Carbon::now()->format('d/m/Y');
            $hasToday = false;
            foreach ($dailyReports as $dr){
                $dr->is_for_field = true;
                if($dr->created_at == $today){
                    $hasToday = true;
                }
            }

            return view('admin.daily_reports.patient_reports', [
                    'items' => $dailyReports,
                    'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
                    'patientId'=>$patient->id,
                    'hasToday'=>$hasToday,
                    'my_reports' => $request->user()->isPatient() && $request->user()->patientInfo->id == $patient->id,
                    'accountId' => $request->user()->id,
                    'role' => $request->user()->role()
            ]);
        }else{
            return redirect('login');
        }
    }

    public function edit(Request $request,Patients $patient, DailyReport $dailyReport){


        if(Auth::check()) {
            $this->authorize('view', [DailyReport::class, $patient, $dailyReport]);

            $dailyReportQuestions = DailyReportQuestion::query()->orderBy('label')->get();

            $dailyReport->is_for_field = true;
            return view('admin.daily_reports.view', [
                'daily_report'=>$dailyReport->id,
                'patientId'=>$patient->id,
                'accountId' => $request->user()->id,
                'data' => $dailyReport,
                'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
                'formAction' => 'view',
                'questions' => $dailyReportQuestions,
                'my_reports' => $request->user()->isPatient() && $request->user()->patientInfo->id == $patient->id
            ]);
        }else{
            return redirect('login');
        }
    }

    private function createNotification($record){
        $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
        $yesterdayReport = DailyReport::where('patient_id','=',$record->patient_id)->whereDate('created_at', $yesterday )->first();
        $patient = Patients::where('id','=',$record->patient_id)->first();
        $type = 0;
        /*
         Notification Types:
         1 = Call your doc!
         2 = Go to hospital!
         3 = Your patient %name% should call you!
         4 = Your patient %name% should go to hospital
         */
        $rule = 0;
        /*
         Rules:
         1) Two days in a row Q1 „yes“ 				    -> Notification: Call your doctor!
         2) Q1, Q2 and Q3 answered with „yes“ 			-> Notification: Call your doctor!
         3) Q3a or Q3b answered with „yes“ 			    -> Notification: Call your doctor!
         4) Q3c answered with yes 					    -> Notification: Go to the hospital!
         5) Two days in a row Q5 „yes“ 				    -> Notification: Call your doctor!
         */
        if ($record->q1 == 1 && $record->q2 == 1 && $record->q3 == 1){
            $type = 1;
            $rule = 2;
            if($record->q3c == 1)
                $type = 2;
        }
        else if (($record->q3a == 1 || $record->q3b == 1) && $record->q3c != 1) {
            $type = 1;
            $rule = 3;
        }else if ($record->q3c == 1) {
            $type = 2;
            $rule = 4;
        }

        if($yesterdayReport) {
            if ($record->q1 == 1 && $yesterdayReport->q1 == 1) {
                /*
                 if twoDays == 1 -> rule 1
                 if twoDays == 5 -> rule 5
                 if twoDays == 6 -> both apply
                 */
                $type = 1;
                $rule = 1;
            }
            if ($record->q5 == 1 && $yesterdayReport->q5 == 1) {
                /*
                 if twoDays == 1 -> rule 1
                 if twoDays == 5 -> rule 5
                 if twoDays == 6 -> both apply
                 */
                $type = 1;
                $rule = 5;
            }
        }


        if($type > 0){
            $patientName = $patient->account->first_name.' '.$patient->account->last_name;
            $pattext = ($type == 1)? 'Παρακαλώ, καλέστε τον ιατρό σας! ('.$patient->doctor->mobile.')' : 'Παρακαλώ μεταβείτε στο νοσοκομείο!';
            $doctext = ($type == 1)? 'Ο ασθενής σας '.$patientName.' πρέπει να σας καλέσει άμεσα!'  : 'Ο ασθενής σας '.$patientName.' πρέπει να μεταβεί άμεσα στο νοσοκομείο!';
            switch ($rule){
                case 2:
                    $doctext =$doctext." Στην αναφορά του ο ασθενής δήλωσε οτι αυξήθηκε η δύσπνοια, αυξήθηκε ο βήχας του και οτι ";
                    $sputum = 'το χρώμα του σάλιου του άλλαξε';
                    if ($record->q3a) $sputum = 'το χρώμα του σάλιου του άλλαξε σε κίτρινο';
                    if ($record->q3b) $sputum = 'το χρώμα του σάλιου του άλλαξε σε πράσινο';
                    if ($record->q3c) $sputum = 'το σάλιο του περιείχε αίμα!';
                    $doctext = $doctext.' '.$sputum;
                    break;
                case 3:
                    $doctext = $doctext." Στην αναφορά του ο ασθενής δήλωσε οτι ";
                    $sputum = 'το χρώμα του σάλιου του άλλαξε';
                    if ($record->q3a) $sputum = 'το χρώμα του σάλιου του άλλαξε σε κίτρινο';
                    if ($record->q3b) $sputum = 'το χρώμα του σάλιου του άλλαξε σε πράσινο';
                    $doctext = $doctext.' '.$sputum;
                    break;
                case 4:
                    $doctext = $doctext." Στην αναφορά του ο ασθενής δήλωσε οτι το σάλιο του περιείχε αίμα!";
                    break;
                case 1:
                    $doctext = $doctext." Στις αναφορές του ο ασθενής δήλωσε οτι αυξήθηκε η δύσπνοινα του για 2 συνεχόμενες μέρες!";
                    break;
                case 5:
                    $doctext = $doctext." Στις αναφορές του ο ασθενής δήλωσε οτι αύξησε τα φάρμακα του για 2 συνεχόμενες μέρες!";
                    break;
            }
            $patNot = new Notifications();
            $patNot->account_id = $patient->account->id;
            $patNot->notification_message = $pattext;
            $patNot->notification_type = $type;
            $result['success1'] = $patNot->save();

            $docNot = new Notifications();
            $docNot->account_id = $patient->doctor->id;
            $docNot->notification_message = $doctext;
            $docNot->notification_type = $type;

            $result['success2'] = $docNot->save();

            $patSmsEnabled = false;
            $docSmsEnabled = false;
            $docPushEnabled = false;

            $docEmailEnabled = false;
            $patEmailEnabled = false;
            $patPushEnabled = false;

            if($patient->account->notification_enabled) { //check if patient has enabled notifications
                switch ($patient->account->notification_mode) {
                    case 'email':
                        $patEmailEnabled = true;
                        break;
                    case 'sms':
                        $patSmsEnabled = true;
                        break;
                    case 'push':
                        $patPushEnabled = true;
                        break;
                }
            }

            if($patient->doctor->notification_enabled) { //check if doctor has enabled notifications
                switch ($patient->doctor->notification_mode) {
                    case 'email':
                        $docEmailEnabled = true;
                        break;
                    case 'sms':
                        $docSmsEnabled = true;
                        break;
                    case 'push':
                        $docPushEnabled = true;
                        break;
                }
            }

            if($patSmsEnabled || $docSmsEnabled){
                $this->sendSMS($patSmsEnabled, $docSmsEnabled, $patient->account, $patient->doctor, $pattext,$doctext);
            }
            if($patEmailEnabled || $docEmailEnabled){
                $this->sendEmail($patEmailEnabled, $docEmailEnabled, $patient->account, $patient->doctor, $pattext,$doctext);
            }
            if($patPushEnabled || $docPushEnabled){
                $this->sendNotification($patPushEnabled, $docPushEnabled, $patient->account, $patient->doctor, $pattext,$doctext);
            }
        }
    }

    private function sendNotification($patPushEnabled, $docPushEnabled,$patAccount, $docAccount, $patText, $docText)
    {
        if ($patPushEnabled) {
            $firebaseToken = AccountDeviceToken::where('account_id', '=', $patAccount->id)->pluck('device_token')->all();
            foreach ($firebaseToken as $token) {
                $data = [
                    "to" => $token,
                    "title" => "AΝΑΠΝΕΩ - ΕΠΕΙΓΟΝ",
                    "body" => $patText
                ];
                $dataString = json_encode($data);

                $headers = [
                    'Content-Type: application/json',
                ];

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://exp.host/--/api/v2/push/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

                $response = curl_exec($ch);
            }
        }
        if ($docPushEnabled) {
            $firebaseToken = AccountDeviceToken::where('account_id', '=', $docAccount->id)->pluck('device_token')->all();
            foreach ($firebaseToken as $token) {
                $data = [
                    "to" => $token,
                    "title" => "AΝΑΠΝΕΩ - ΕΠΕΙΓΟΝ",
                    "body" => $docText
                ];
                $dataString = json_encode($data);

                $headers = [
                    'Content-Type: application/json',
                ];

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://exp.host/--/api/v2/push/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

                $response = curl_exec($ch);
                //var_dump($token);
            }
        }
        /*        dd($response);*/
    }

    private function sendEmail($patEmailEnabled, $docEmailEnabled,$patAccount, $docAccount, $patEmailText, $docEmailText){
        $mailSettings = [
            'host' => 'email-smtp.eu-west-1.amazonaws.com',
            'port' => '587',
            'username' => 'AKIAVELZI4LG7BOONHNQ',
            'password' => 'BCtcQrgPvkRTShDNtgsgA8nLbIAAYUNQEwlFzmZVCM9M',
            'encryption' => 'tls',
            'from_email' => 'noreply@openit.gr',
            'from_name' => 'Αναπνέω Beta',
        ];
        $mailer = app()->makeWith('company.mailer', $mailSettings);

        if ($patEmailEnabled) { //if patient has enabled email as notfication mode

            $mailer->to($patAccount->email)->send(
                new NotificationMail($patAccount, $patEmailText)
            );
        }
        if ($docEmailEnabled) { //if doctor has enabled email as notfication mode
            $mailer->to($docAccount->email)->send(
                new NotificationMail($docAccount, $docEmailText)
            );
        }
    }


    private function sendSMS($patSmsEnabled, $docSmsEnabled,$patAccount, $docAccount, $patSmsText, $docSmsText)
    {

        $urlToHit = 'https://www.opensms.gr/api/httpapiv2.php';

        $date = new DateTime("now", new DateTimeZone("Europe/Athens"));
        $toSend = $date->format('d-m-Y H:i:s');

        if ($patSmsEnabled) { //if patient has enabled sms as notfication mode

            $to = array();
            if (!is_null($patAccount->mobile)) {

                array_push($to, $patAccount->mobile);
                $toString = implode(',', $to);

                $fields = array(
                    'username' => 'opensmsanapneo',
                    'password' => 'opensmsANAPNEO123!',
                    'label' => 'ANAPNEO',
                    'to' => $toString,
                    'smsmessage' => $toSend.': '.$patSmsText
                );

                $fields_string = "";
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }

                rtrim($fields_string, '&');

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_URL, $urlToHit);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_VERBOSE, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

                $result = curl_exec($ch);
                $xml = simplexml_load_string($result);

                if (isset($xml->result)) {
                    $returnArray['success'] = (boolean)$xml->result;
                } else {
                    $returnArray['success'] = false;
                }

                if (isset($xml->transactionID)) {
                    $returnArray['transactionID'] = (string)$xml->transactionID;
                }

                if (isset($xml->messagesSent)) {
                    $returnArray['messagesSent'] = (string)$xml->messagesSent;
                }

                $msgIds = '';
                if (isset($xml->messagesIDs)) {
                    foreach ($xml->messagesIDs as $mId) {
                        $msgIds .= (string)$mId->id . ',';
                    }
                    $msgIds = rtrim($msgIds, ',');
                    $returnArray['messagesIDs'] = $msgIds;
                }

                if (isset($xml->error->code)) {
                    $myError = (string)$xml->error->code;
                    $myError = str_pad($myError, 3, "0", STR_PAD_LEFT);
                    $returnArray['error'] = array();
                    $returnArray['error']['errorcode'] = $myError;
                    $returnArray['error']['errormsg'] = (string)$xml->error->description;

                    $mailMessage = "Error: " . $returnArray['error']['errorcode'] . '<br>';
                    $mailMessage .= "Description: " . $returnArray['error']['errormsg'] . '<br>';

                    echo $mailMessage;
                }
            }
        }
        if ($docSmsEnabled) { //if doctor has enabled sms as notfication mode
            sleep ( 1);

            $to = array();
            if (!is_null($docAccount->mobile)) {

                array_push($to, $docAccount->mobile);
                $toString = implode(',', $to);

                $fields = array(
                    'username' => 'opensmsanapneo',
                    'password' => 'opensmsANAPNEO123!',
                    'label' => 'ANAPNEO',
                    'to' => $toString,
                    'smsmessage' => $toSend.': '.$docSmsText
                );

                $fields_string = "";
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }

                rtrim($fields_string, '&');

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_URL, $urlToHit);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_VERBOSE, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

                $result = curl_exec($ch);
                $xml = simplexml_load_string($result);

                if (isset($xml->result)) {
                    $returnArray['success'] = (boolean)$xml->result;
                } else {
                    $returnArray['success'] = false;

                }

                if (isset($xml->transactionID)) {
                    $returnArray['transactionID'] = (string)$xml->transactionID;
                }

                if (isset($xml->messagesSent)) {
                    $returnArray['messagesSent'] = (string)$xml->messagesSent;
                }

                $msgIds = '';
                if (isset($xml->messagesIDs)) {
                    foreach ($xml->messagesIDs as $mId) {
                        $msgIds .= (string)$mId->id . ',';
                    }
                    $msgIds = rtrim($msgIds, ',');
                    $returnArray['messagesIDs'] = $msgIds;
                }

                if (isset($xml->error->code)) {
                    $myError = (string)$xml->error->code;
                    $myError = str_pad($myError, 3, "0", STR_PAD_LEFT);
                    $returnArray['error'] = array();
                    $returnArray['error']['errorcode'] = $myError;
                    $returnArray['error']['errormsg'] = (string)$xml->error->description;

                    $mailMessage = "Error: " . $returnArray['error']['errorcode'] . '<br>';
                    $mailMessage .= "Description: " . $returnArray['error']['errormsg'] . '<br>';

                    echo $mailMessage;
                }
            }
        }
    }

    public function destroy(Request $request, Patients $patient, DailyReport $dailyReport)
    {

        $this->authorize('delete', [DailyReport::class]);
        $dailyReport->is_for_field = true;
        $reportDate = $dailyReport->created_at;

        $result['success'] = $dailyReport->delete();

        $dailyReports = DailyReport::where('patient_id','=',$patient->id)->orderBy('created_at','desc')->get();
        $today = Carbon::now()->format('d/m/Y');
        $hasToday = false;
        foreach ($dailyReports as $dr){
            $dr->is_for_field = true;
            if($dr->created_at == $today){
                $hasToday = true;
            }
        }

        return redirect()->route('daily_reports.patients.reports', [
            'items' => $dailyReports,
            'patientName' => $patient->account->first_name.' '.$patient->account->last_name,
            'patientId'=>$patient->id,
            'patient' => $patient->id,
            'hasToday'=>$hasToday,
            'my_reports' => $request->user()->isPatient() && $request->user()->patientInfo->id == $patient->id,
            'accountId' => $request->user()->id,
            'role' => $request->user()->role()
        ])->with('success', 'Η ημερήσια αναφορά για '.$reportDate.' διαγράφηκε επιτυχώς!');

        //return redirect()->route('ccqs.patients.list', ['patient'=> $patient->id,'items' => $ccqs])

    }

}
