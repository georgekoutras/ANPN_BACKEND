<?php

namespace App\Http\Controllers\Mobile;

use App\Helpers\PaginatorHelper;
use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\DailyReport;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationControllerMobile extends Controller
{
    public function index(Request $request, Accounts $account){
        $this->authorize('indexMobile',[Notifications::class, $account]);

        $input = $request->all();
        $validator = PaginatorHelper::getValidator($input);

        if ($validator->fails()) {
            return response()->json(['rows' => [], 'total' => 0]);
        }

        $sortArray = PaginatorHelper::determineSorting($request, Notifications::DEFAULT_SORTING());

        switch ($sortArray['sort']) {
            case "id":
                $sortArray['sort'] = 'notifications.id';
                break;
            case "date":
                $sortArray['sort'] = 'notifications.notification_date';
                break;

        }
        $notifications = Notifications::query()->where('account_id', '=', $account->id);

        $notifications = $notifications->select([
            'notifications.id',
            'notifications.account_id',
            'notifications.notification_message',
            'notifications.created_at']);

        $notifications = $notifications->orderBy($sortArray['sort'], $sortArray['order']);
        $notifications = $notifications->paginate($request->input('limit'))->toArray();

        $result['success'] = true;
        $result['data'] = PaginatorHelper::format($notifications);
        return response()->json($result, 200);
    }
}
