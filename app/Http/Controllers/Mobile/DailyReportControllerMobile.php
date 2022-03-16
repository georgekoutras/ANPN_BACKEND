<?php

namespace App\Http\Controllers\Mobile;

use App\Helpers\PaginatorHelper;
use App\Http\Controllers\Controller;
use App\Mail\NotificationMail;
use App\Models\AccountDeviceToken;
use App\Models\Accounts;
use App\Models\DailyReport;
use App\Models\Notifications;
use App\Models\Patients;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DailyReportControllerMobile extends Controller
{
    public function getAllPatientReports(Request $request, Patients $patient)
    {
        $this->authorize('allReports', [DailyReport::class, $patient]);

        $input = $request->all();
        $validator = PaginatorHelper::getValidator($input);

        if ($validator->fails()) {
            return response()->json(['rows' => [], 'total' => 0]);
        }

        $sortArray = PaginatorHelper::determineSorting($request, DailyReport::DEFAULT_SORTING());

        switch ($sortArray['sort']) {
            case "id":
                $sortArray['sort'] = 'daily_reports.id';
                break;
            case "patient_id":
                $sortArray['sort'] = 'patients.id';
                break;
            case "date":
                $sortArray['sort'] = 'daily_reports.created_at';
                break;

        }
        $dailyReports = DailyReport::query()->where('patient_id', '=', $patient->id)
            ->leftJoin('patients', 'patients.id', '=', 'daily_reports.patient_id');

        $dailyReports = $dailyReports->select([
            'daily_reports.id',
            'daily_reports.created_at as date',
            'patients.id as patient_id' ]);

        $dailyReports = $dailyReports->orderBy($sortArray['sort'], $sortArray['order']);
        $dailyReports = $dailyReports->paginate($request->input('limit'))->toArray();

        $result['success'] = true;
        $result['data'] = PaginatorHelper::format($dailyReports);

        return response()->json($result, 200);
    }

    public function getPatientReport(Request $request, Patients $patient, DailyReport $dailyReport)
    {
        $this->authorize('view', [DailyReport::class, $patient, $dailyReport]);

        $result['success'] = true;
        $dailyReport = $dailyReport->load(['patient:id,social_id,account_id','patient.account:id,first_name,last_name']);
        $result['data'] = $dailyReport;

        return response()->json($result);
    }

    public function store(Request $request, Patients $patient){
        $this->authorize('store', [DailyReport::class, $patient]);

        $validator = Validator::make($request->all(),[
/*            'patient_id' => [
                'required',
                'integer',
                Rule::exists('patients', 'id')->whereNull('deleted_at')
            ],*/
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
        }

        $dailyReport = new DailyReport();
        $dailyReport->fill($request->all());
        $dailyReport->patient_id = $patient->id;
        $result['success'] = $dailyReport->save();
        $this->createNotification($dailyReport);

        return response()->json($result);
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

                    //echo $mailMessage;
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

                    //echo $mailMessage;
                }
            }
        }
    }

    public function hasReport(Request $request, Patients $patient){
        $dailyReports = DailyReport::where('patient_id','=',$patient->id)->orderBy('created_at','desc')->get();
        $today = Carbon::now()->format('d/m/Y');
        $hasToday = false;
        foreach ($dailyReports as $dr){
            $dr->is_for_field = true;
            if($dr->created_at == $today){
                $hasToday = true;
                break;
            }
        }

        $result['success'] = true;
        $result['data'] = $hasToday;
        return response()->json($result);
    }
}
