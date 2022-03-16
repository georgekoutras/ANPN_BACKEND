<?php


namespace App\Auth;


use App\Models\AccessToken;
use App\Models\Accounts;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\Authenticatable;

class TokenToUserProvider implements UserProvider
{
    private $accessToken;
    private $user;

    public function __construct(Accounts $user, AccessToken $accessToken)
    {
        $this->user = $user;
        $this->accessToken = $accessToken;
    }

    public function retrieveById($identifier)
    {
        return $this->user->find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        $token = $this->accessToken->with('user')->where($identifier, $token)->first();

        return $token && $token->user ? $token->user : null;
    }

    public function retrieveByCredentials(array $credentials)
    {
        $query = $this->user->newQuery();
        foreach($credentials as $credentialKey => $credentialValue){
            if (!Str::contains($credentialKey, 'password')) {
                $value = $credentialValue;
                $query->where($credentialKey, $value);
            }

        }

        return $query->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials) {
        $plain = $credentials['password'];

        return Hash::check($plain, $user->getAuthPassword());
    }

    public function updateRememberToken (Authenticatable $user, $token) {
        // update via remember token not necessary
    }
}
