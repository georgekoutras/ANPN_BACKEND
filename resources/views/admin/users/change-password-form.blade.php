<div class="form-group">
    <label>
        <h5>
            {{ trans('app.current_password') }} <span class="required">*</span>
        </h5>
    </label>
    <div>
        <input id="curr_password" class="form-control" name="curr_password" type="password" data-parsley-maxlength="255"
               data-parsley-required="true" data-parsley-minlength="4"/>
    </div>
</div>
<div class="form-group">
    <label>
        <h5>
            {{ trans('app.new_password') }} <span class="required">*</span>
        </h5>
    </label>
    <div>
        <input id="password" class="form-control" name="password" type="password" data-parsley-maxlength="255"
               data-parsley-required="true" data-parsley-equalto="#repeat_password" data-parsley-minlength="4"/>
    </div>
</div>

<div class="form-group">
    <label>
        <h5>
            {{ trans('app.repeat_password') }} <span class="required">*</span>
        </h5>
    </label>
    <div>
        <input id="repeat_password" class="form-control" name="repeat_password" type="password" data-parsley-maxlength="255"
               data-parsley-required="true" data-parsley-equalto="#password" data-parsley-minlength="4"/>
    </div>
</div>
