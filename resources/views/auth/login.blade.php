@extends('layouts.app')

@section('content')
    <div class="login-form">
        <h4 class="fw-300 c-grey-900 mB-40">Είσοδος</h4>
        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="text-normal text-dark">Email</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" oninvalid="this.setCustomValidity('Παρακαλώ εισάγετε το email σας')"  oninput="setCustomValidity('')" required>

                @if ($errors->has('email'))
                    <span class="form-text text-danger">
                        <small>{{ $errors->first('email') }}</small>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="text-normal text-dark">Κωδικός</label>
                <input id="password" type="password" class="form-control" name="password" oninvalid="this.setCustomValidity('Παρακαλώ εισάγετε τον κωδικό πρόσβασης')"  oninput="setCustomValidity('')" required>

                @if ($errors->has('password'))
                    <span class="form-text text-danger">
                        <small>{{ $errors->first('password') }}</small>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <div class="peers ai-c jc-sb fxw-nw">
                    <div class="peer">
                        <button class="btn btn-primary">Είσοδος</button>
                    </div>
                </div>
            </div>
    <!--        <div class="peers ai-c jc-sb fxw-nw">
                <div class="peer">
                    <a class="btn btn-link" href="">
                        Ξεχάσατε τον κωδικό σας;
                    </a>
                </div>
                <div class="peer">
                    <a href="/register" class="btn btn-link">Δημιουργία λογαριασμού</a>
                </div>
            </div>-->
        </form>
    </div>

@endsection
