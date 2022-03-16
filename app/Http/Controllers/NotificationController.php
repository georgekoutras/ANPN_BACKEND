<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\DailyReport;
use App\Models\Notifications;
use App\Traits\FormatDates;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use FormatDates;

    public function index(Request $request)
    {
        if(Auth::check()) {
            $this->authorize('index',[Notifications::class]);

            if($request->user()->role == 'administrator') {
                $accounts = Accounts::query()->where('role','!=','administrator');
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
            ]);
            return view('admin.notifications.index', ['items' => $accounts->get(), 'loggedRole'=>$request->user()->role,'loggedId'=>$request->user()->id]);
        }else{
            return redirect('login');
        }

    }

    public function accountNotifications(Request $request,Accounts $account)
    {
        if(Auth::check()) {
            $this->authorize('accountNotifications', [Notifications::class, $account]);

            $notifications = Notifications::where('account_id','=',$account->id)->orderBy('created_at','desc')->get();
            $today = Carbon::now()->format('d/m/Y');
            foreach ($notifications as $dr){
                $dr->is_for_field = true;
            }

            return view('admin.notifications.account_notifications', [
                'items' => $notifications,
                'accountName' => $account->first_name.' '.$account->last_name,
                'accountId'=>$account->id,
                'my_notifications'=>$request->user()->id == $account->id,
                'patientId' => $request->user()->isPatient() ? $request->user()->patientInfo->id : null
                ]);
        }else{
            return redirect('login');
        }
    }
}
