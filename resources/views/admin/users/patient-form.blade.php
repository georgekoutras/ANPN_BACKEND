
<div class="row mB-40">
    <div class="col-sm-8">
        <div class="bgc-white p-20 bd">
            @if ($formAction == 'create')
                <input type="hidden" name="_method" value="POST" />
            @elseif($formAction == 'edit')
                <input type="hidden" name="_method" value="PUT" />
            @endif
            <input type="hidden" name="role" value="{{ $role }}">

            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.first_name') }} <span class="required">*</span>
                    </h5>
                </label>
                <div>
                    <input id="first_name" class="form-control" name="first_name" type="text" data-parsley-maxlength="255"
                           data-parsley-required="true" />
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.second_name') }}
                    </h5>
                </label>
                <div>
                    <input id="second_name" class="form-control" name="second_name" type="text" data-parsley-maxlength="255"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.last_name') }} <span class="required">*</span>
                    </h5>
                </label>
                <div>
                    <input id="last_name" class="form-control" name="last_name" type="text" data-parsley-maxlength="255"
                           data-parsley-required="true" />
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.email') }} <span class="required">*</span>
                    </h5>
                </label>
                <div>
                    <input id="email" class="form-control" name="email" type="email" data-parsley-maxlength="255"
                           data-parsley-required="true" />
                </div>
            </div>
            @if ($formAction == 'create')
                <div class="form-group">
                    <label>
                        <h5>
                            {{ trans('app.password') }} <span class="required">*</span>
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
            @endif
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.mobile') }} <span class="required">*</span>
                    </h5>
                </label>
                <div>
                    <input id="mobile" class="form-control" name="mobile" type="number" data-parsley-maxlength="255"
                           data-parsley-required="true" />
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.notification_enabled') }}<span class="required">*</span>
                    </h5>
                </label>
                <div class="radiobox-container">
                    {{ trans('app.yes') }} <input type="radio" class="flat notification-enabled" id="notification_enabled_yes" name="notification_enabled" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat notification-enabled" id="notification_enabled_no" name="notification_enabled" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group notification-mode" hidden>
                <label>
                    <h5>
                        {{ trans('app.notification_mode') }} <span class="required">*</span>
                    </h5>
                </label>
                <div class="notification-type-cont">
                    <select  style="width:100%" data-parsley-required="true" class="notification_mode form-control" data-parsley-class-handler=".notification-type-cont" id="notification_mode" name="notification_mode" >
                        <option value="sms">Sms</option>
                        <option value="push">Push Notifications</option>
                        <option value="email">Email</option>
                    </select>
                </div>
            </div>
            @if($loggedRole == 'administrator')
                <div class="form-group">
                    <label>
                        <h5>
                            {{ trans('app.doctor') }} <span class="required">*</span>
                        </h5>
                    </label>
                    <div class="s1-cont">
                        <select style="width:100%" data-parsley-class-handler=".s1-cont" data-parsley-errors-container="#select-error1" class="doctor_id form-control" data-parsley-required="true" id="doctor_id" name="doctor_id"><option></option></select>
                        <div id="select-error1"></div>
                    </div>
                </div>
            @else
                    <input id="doctor_id" class="form-control" name="doctor_id" type="text" value="{{$loggedId}}" hidden/>
            @endif
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.social_id') }} <span class="required">*</span>
                    </h5>
                </label>
                <div>
                    <input id="social_id" class="form-control" name="social_id" type="number"  data-parsley-length="[11,11]" data-parsley-length-message="Το ΑΜΚΑ πρέπει να έχει ακριβώς 11 αριθμούς." data-parsley-type="digits"
                           data-parsley-required="true" />
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.sex') }} <span class="required">*</span>
                    </h5>
                </label>
                <div class="sex-cont">
                    <select  style="width:100%" data-parsley-required="true" class="notification_mode form-control"  data-parsley-errors-container="#select-error11" data-parsley-class-handler=".sex-cont" id="sex" name="sex" >
                        <option value="0">{{ trans('app.female') }}</option>
                        <option value="1">{{ trans('app.male') }}</option>
                    </select>
                    <div id="select-error11"></div>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.birth_date') }} <span class="required">*</span>
                    </h5>
                </label>
                <div class="inner-addon right-addon">
                    <i class="glyphicon ti-calendar"></i>
                    <input type="text" class="form-control" id="birth_date" name="birth_date" data-parsley-required="true" readonly/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.first_diagnose_date') }} <span class="required">*</span>
                    </h5>
                </label>
                <div class="inner-addon right-addon">
                    <i class="glyphicon ti-calendar"></i>
                    <input type="text" class="form-control" id="first_diagnose_date" name="first_diagnose_date" data-parsley-required="true" readonly/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.address') }} <span class="required">*</span>
                    </h5>
                </label>
                <div>
                    <input id="address" class="form-control" name="address" type="text"
                           data-parsley-required="true" />
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.landline') }}
                    </h5>
                </label>
                <div>
                    <input id="land_line" class="form-control" name="land_line" type="number"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.file_id') }} <span class="required">*</span>
                    </h5>
                </label>
                <div>
                    <input id="file_id" class="form-control" name="file_id" type="text"
                           data-parsley-required="true" />
                </div>
            </div>
        </div>
    </div>

</div>
