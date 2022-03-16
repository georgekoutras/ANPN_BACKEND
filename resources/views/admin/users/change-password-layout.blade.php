<div class="modal-header">
    <h4>{{trans('app.change_password')}}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body">
    <form action="{{ route('accounts.change_password', $data->id) }}" method="POST" class="form-horizontal edit_user" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled]">
        @csrf
        @include('admin.users.change-password-form')

        <button type="submit" class="btn btn-primary">{{ trans('app.save_button') }}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('app.cancel') }}</button>
    </form>
</div>
<div class="modal-footer">

</div>
