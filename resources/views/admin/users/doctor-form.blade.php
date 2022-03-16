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
                               data-parsley-required="true" data-parsley-equalto="#password" data-parsley-minlength="4" />
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
        </div>
    </div>

</div>
