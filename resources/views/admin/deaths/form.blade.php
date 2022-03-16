<div class="row mB-40">
    <div class="col-sm-8">
        <div class="bgc-white p-20 bd">
            @if ($formAction == 'create')
                <input type="hidden" name="_method" value="POST" />
            @elseif($formAction == 'edit')
                <input type="hidden" name="_method" value="PUT" />
            @endif
                <div class="form-group">
                    <label>
                        <h5>
                            {{ trans('app.patient') }} <span class="required">*</span>
                        </h5>
                    </label>
                    <div class="s1-cont">
                        <select style="width:100%" data-parsley-class-handler=".s1-cont" data-parsley-errors-container="#select-error1" class="patient_id form-control" data-parsley-required="true" id="patient_id" name="patient_id"><option></option></select>
                        <div id="select-error1"></div>
                    </div>
                </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.death_date') }} <span class="required">*</span>
                    </h5>
                </label>
                <div class="inner-addon right-addon">
                    <i class="glyphicon ti-calendar"></i>
                    <input type="text" class="form-control" id="date" name="date" data-parsley-required="true" readonly/>
                </div>
            </div>

            <div class="form-group ">
                <label>
                    <h4>
                        {{ trans('app.cardiovascular')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="cardiovascular_yes" name="cardiovascular" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="cardiovascular_no" name="cardiovascular" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group ">
                <label>
                    <h4>
                        {{ trans('app.respiratory')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="respiratory_yes" name="respiratory" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="respiratory_no" name="respiratory" value="0" checked="checked">
                </div>
            </div>            <div class="form-group ">
                <label>
                    <h4>
                        {{ trans('app.infectious_disease')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="infectious_disease_yes" name="infectious_disease" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="infectious_disease_no" name="infectious_disease" value="0" checked="checked">
                </div>
            </div>            <div class="form-group ">
                <label>
                    <h4>
                        {{ trans('app.malignancy')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="malignancy_yes" name="malignancy" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="malignancy_no" name="malignancy" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.notes') }}
                    </h5>
                </label>
                <div>
                    <textarea id="notes" rows="5" class="form-control" name="notes" data-parsley-maxlength="12000"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
