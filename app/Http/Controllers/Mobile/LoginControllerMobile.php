<?php


namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\AccessToken;
use App\Models\Accounts;
use App\Models\AccountDeviceToken;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LoginControllerMobile extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    private $validator;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }


    public function username()
    {
        return 'email';
    }


    public function login(Request $request){
        if ($this->validateLogin($request)){
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => trans('auth.missing_fields'),
                    'code' => 497
                ]
            ]);
            /*return response()->json([$this->validator->errors()]);

            throw new ValidationException($this->validator);*/
        }

        $user = null;
        if ($this->attemptLogin($request)){
            $user = $this->guard()->user();


            $accessToken = AccessToken::generateAccessToken($user);
            $accessToken->save();

            Event::dispatch(new Login('access_token', $user, false));

            return $this->sendLoginResponse($request, $user, $accessToken);
        }

        return $this->sendFailedLoginResponse($request,$user);
    }

    protected function validateLogin(Request $request)
    {
        $this->validator = Validator::make($request->only($this->username(), 'password'), [
            $this->username() => 'required|string',
            'password' => 'required|string'
        ]);

        return $this->validator->fails();
    }

    protected function attemptLogin(Request $request){
        return $this->guard()->attempt(
            $this->credentials($request), false
        );
    }

    protected function guard(){
        return Auth::guard('access_token');
    }


    public function sendFailedLoginResponse(Request $request, Accounts $user = null)
    {

        if (is_null($user)){
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => trans('auth.failed_login'),
                    'code' => 496
                ]
            ]);
        }
        return response()->json([]);
    }

    protected function sendLoginResponse(Request $request, Accounts $user, AccessToken $accessToken)
    {

        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $accessToken->access_token,
                'refresh_token' => $accessToken->refresh_token,
                'full_name' => $user->getFullName(),
                'role' => $user->role(),
                'account_id' => $user->id,
                'patient_id' => $user->isPatient() ? $user->patientInfo->id : null
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function refresh(Request $request){
        $validator = $this->refreshRequestValidator($request);
        if ($validator->fails()){
            throw new ValidationException($validator);
        }

        $oldAccessToken = AccessToken::where('access_token', $request->input('access_token'))->first();

        $decrypted = Crypt::decryptString(base64_decode($request->input('access_token')));
        $exploded = explode('|', $decrypted);

        $user = Accounts::where('id', '=', $exploded[0])->first();

        $accessToken = AccessToken::generateAccessToken($user);
        $accessToken->save();
        $oldAccessToken->delete();

        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $accessToken->access_token,
                'refresh_token' => $accessToken->refresh_token
            ]
        ]);

    }

    protected function refreshRequestValidator(Request $request){
        $validator = Validator::make($request->all(), [
            'access_token' => [
                'required',
                function($attribute, $value, $fail) use ($request){
                    $decrypted = Crypt::decryptString(base64_decode($value));
                    $exploded = explode('|', $decrypted);

                    $token = AccessToken::where('account_id', '=', $exploded[0])
                        ->where('access_token', '=', $value)->first();

                    if (is_null($token)){
                        return $fail(trans('app.field_invalid'));
                    }
                }
            ],
            'refresh_token' => [
                'required', 'exists:access_tokens,refresh_token',
                function($attribute, $value, $fail) use($request) {
                    $decrypted = Crypt::decryptString(base64_decode($request->input('access_token')));
                    $exploded = explode('|', $decrypted);
                    $token = AccessToken::where('account_id', '=', $exploded[0])
                        ->where('refresh_token', '=', $value)->first();
                    if (is_null($token)){
                        return $fail(trans('app.field_invalid'));
                    }

                    if ($token->access_token !== $request->input('access_token')){
                        return $fail(trans('app.match_pair'));
                    }
                }
            ]
        ]);

        return $validator;
    }

    public function valid(){
        return response()->json(['success'=>true], 200);
    }

    public function logout(Request $request){
        $header = $request->header('Access-Token');

/*        if (!$request->has('access_token') || strlen($request->input('access_token')) == 0
            || !$request->has('refresh_token') || strlen($request->input('refresh_token')) == 0)
        {
            return response()->json(['success1' => false]);
        }*/
        if (!$request->has('access_token') || strlen($request->input('access_token')) == 0)
        {
            return response()->json(['success' => false]);
        }else if ($header !== $request->input('access_token')){
            return response()->json(['success' => false]);
        }else{
/*            $accessToken = AccessToken::where('access_token', $request->input('access_token'))
                ->where('refresh_token', $request->input('refresh_token'))
                ->first();*/
            $accessToken = AccessToken::where('access_token', $request->input('access_token'))
                ->first();

            if (!is_null($accessToken)){
                $accountId = $accessToken->account_id;
                $accessToken->delete();
                if($request->has('device_token') && strlen($request->input('device_token')) > 0){
                    $token = AccountDeviceToken::query()
                        ->where('device_token','=',$request->input('device_token'))
                        ->where('account_id','=',$accountId)->delete();
                }
                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function addDeviceToken(Request $request,Accounts $account){
        if (!$request->has('device_token') || strlen($request->input('device_token')) == 0)
        {
            return response()->json(['success' => false]);
        }

        $validator = Validator::make($request->all(),[
            'device_token' =>'required|string',
            'device_type' => [
                'required',
                'string',
                Rule::in(['android','ios'])
            ]
        ]);

        if ($validator->fails()){
            throw new ValidationException($validator);
        }else {
            $dtExists = AccountDeviceToken::where('device_token','=',$request->input('device_token'))->get();
            if(count($dtExists) > 0) {
                AccountDeviceToken::where('device_token', '=', $request->input('device_token'))->delete();
            }
            $deviceToken = new AccountDeviceToken();
            $deviceToken->account_id = $account->id;
            $deviceToken->device_token = $request->input('device_token');
            $deviceToken->device_type = $request->input('device_type');
            $result['success'] = $deviceToken->save();
            return response()->json(['success' => true]);
        }

/*        if ($validator->fails()){
            //throw new ValidationException($validator);
        }else {
            $dtExists = AccountDeviceToken::where('device_token','=',$request->input('device_token'))->first();
            if(count($dtExists)>0){
                var_dump($dtExists->account_id);
                var_dump($dtExists->device_token);

                $dtExists->account_id = $account->id;
                $dtExists->save();
            }else {
                var_dump("new");
                var_dump($dtExists->account_id);
                var_dump($request->input('device_token'));
                $deviceToken = new AccountDeviceToken();
                $deviceToken->account_id = $account->id;
                $deviceToken->device_token = $request->input('device_token');
                $deviceToken->device_type = $request->input('device_type');
                $result['success'] = $deviceToken->save();
            }
            return response()->json(['success' => true]);
        }*/
    }

    public function showTokens(){
        $tokens = AccountDeviceToken::all();
        return response()->json($tokens);
    }
}
