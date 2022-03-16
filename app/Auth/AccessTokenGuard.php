<?php


namespace App\Auth;


use App\Models\Accounts;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccessTokenGuard implements Guard
{
    use GuardHelpers;

    private $request = null;
    private $inputKey = '';

    private $checkResult = [];

    public function __construct(UserProvider $provider, Request $request, array $configuration)
    {
        $this->provider = $provider;
        $this->request = $request;

        $this->inputKey = isset($configuration['input_key']) ? $configuration['input_key'] : 'access_token';
    }

    public function user(){
        if (!is_null($this->user)){
            return $this->user;
        }

        $token = $this->getTokenByRequest();

        if (!is_null($token)){
            $decoded = base64_decode($token);
            $decrypted = Crypt::decryptString($decoded);

            $exploded = explode('|', $decrypted);

            $accountId = $exploded[0];
            $issuedAt = $exploded[1];
            $expires = $exploded[2];

            $account = Accounts::where('id', $accountId)->first();
            if ($account){
                if (time() >= $expires){

                    $this->checkResult = ['error' => 2];
                    return null;
                }
                $this->user = $account;
            }

        }else {
            $this->checkResult = ['error' => 1];
        }
        return $this->user;
    }

    public function getCheckResult(){
        return $this->checkResult;
    }

    private function getTokenByRequest(){
        $token = $this->request->query($this->inputKey);
        if (empty($token)){
            $token = $this->request->input($this->inputKey);
        }
        if (empty($token)){
            $token = $this->request->header($this->inputKey, null);
        }

        return $token;
    }

    public function validate(array $credentials = []){

        $this->user = $this->provider->retrieveByCredentials($credentials);
        if ($this->user){
            return $this->provider->validateCredentials($this->user, $credentials);
        }else
            return false;
    }

    public function attempt(array $credentials = [], $remember = false){
        return $this->validate($credentials);
    }
}
