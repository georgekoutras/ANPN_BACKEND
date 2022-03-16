<?php


namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    private $validator;

    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if($request->user()->isPatient())
                return redirect()->route('daily_reports.patients.reports',['patient'=>$request->user()->patientInfo->id,'patientId'=>$request->user()->patientInfo->id,'accountId'=>$request->user()->id]);
            else
                return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Το email ή/και ο κωδικός πρόσβασης δεν είναι σωστά.',
        ]);
    }

    protected function validateLogin(Request $request)
    {

        $this->validator = Validator::make($request->only($this->username(), 'password'), [
            $this->username() => 'required|string',
            'password' => 'required|string'
        ]);
        return $this->validator->fails();
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }

}
