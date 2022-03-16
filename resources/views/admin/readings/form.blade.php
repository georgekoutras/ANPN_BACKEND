<div class="row mB-40">
    <div class="col-sm-8">
        <div class="bgc-white p-20 bd">
            @if ($formAction == 'create')
                <input type="hidden" name="_method" value="POST" />
            @elseif($formAction == 'edit')
                <input type="hidden" name="_method" value="PUT" />
            @endif
            <input type="hidden" name="patient_id" value="{{ $patientId }}">
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.diagnose_date') }} <span class="required">*</span>
                    </h5>
                </label>
                <div class="inner-addon right-addon">
                    <i class="glyphicon ti-calendar"></i>
                    <input type="text" class="form-control" id="diagnose_date" name="diagnose_date" data-parsley-required="true" readonly />
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.status') }} <span class="required">*</span>
                    </h5>
                </label>
                <div class="sex-cont">
                    <select  style="width:100%" data-parsley-required="true" class="status form-control"  data-parsley-errors-container="#select-error11" data-parsley-class-handler=".sex-cont" id="status" name="status" >
                        <option value="baseline">{{ trans('app.baseline') }}</option>
                        <option value="exacerbation">{{ trans('app.exacerbation') }}</option>
                    </select>
                    <div id="select-error11"></div>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.weight') }}
                    </h5>
                </label>
                <div>
                    <input id="weight" class="form-control" name="weight" type="number" step="1" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.height') }}
                    </h5>
                </label>
                <div>
                    <input id="height" class="form-control" name="height" type="number" step="1" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.smoker') }}
                    </h5>
                </label>
                <div>
                    <select  style="width:100%" class="status form-control" id="smoker" name="smoker" >
                        <option value=""></option>
                        <option value="0">{{ trans('app.smoker') }}</option>
                        <option value="1">{{ trans('app.non-smoker') }}</option>
                        <option value="2">{{ trans('app.ex-smoker') }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.pxy') }}
                    </h5>
                </label>
                <div>
                    <input id="pxy" class="form-control" name="pxy" type="number" step="1" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.pao2') }}
                    </h5>
                </label>
                <div>
                    <input id="pao2" class="form-control" name="pao2" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.paco2') }}
                    </h5>
                </label>
                <div>
                    <input id="paco2" class="form-control" name="paco2" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.satO2_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="satO2_pro" class="form-control" name="satO2_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.hco3') }}
                    </h5>
                </label>
                <div>
                    <input id="hco3" class="form-control" name="hco3" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.ph') }}
                    </h5>
                </label>
                <div>
                    <input id="ph" class="form-control" name="ph" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.hematocrit') }}
                    </h5>
                </label>
                <div>
                    <input id="hematocrit" class="form-control" name="hematocrit" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fvc') }}
                    </h5>
                </label>
                <div>
                    <input id="fvc" class="form-control" name="fvc" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fvc_pre') }}
                    </h5>
                </label>
                <div>
                    <input id="fvc_pre" class="form-control" name="fvc_pre" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fvc_pre_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="fvc_pre_pro" class="form-control" name="fvc_pre_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fvc_post') }}
                    </h5>
                </label>
                <div>
                    <input id="fvc_post" class="form-control" name="fvc_post" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fvc_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="fvc_pro" class="form-control" name="fvc_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fev1') }}
                    </h5>
                </label>
                <div>
                    <input id="fev1" class="form-control" name="fev1" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fev1_pre') }}
                    </h5>
                </label>
                <div>
                    <input id="fev1_pre" class="form-control" name="fev1_pre" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fev1_pre_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="fev1_pre_pro" class="form-control" name="fev1_pre_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fev1_post') }}
                    </h5>
                </label>
                <div>
                    <input id="fev1_post" class="form-control" name="fev1_post" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fev1_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="fev1_pro" class="form-control" name="fev1_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.del_fev1_post') }}
                    </h5>
                </label>
                <div>
                    <input id="del_fev1_post" class="form-control" name="del_fev1_post" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fev1_fvc') }}
                    </h5>
                </label>
                <div>
                    <input id="fev1_fvc" class="form-control" name="fev1_fvc" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fev1_fvc_pre') }}
                    </h5>
                </label>
                <div>
                    <input id="fev1_fvc_pre" class="form-control" name="fev1_fvc_pre" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.fef25_75_pre_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="fef25_75_pre_pro" class="form-control" name="fef25_75_pre_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.del_fef25_75_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="del_fef25_75_pro" class="form-control" name="del_fef25_75_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.pef_pre_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="pef_pre_pro" class="form-control" name="pef_pre_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.del_pef_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="del_pef_pro" class="form-control" name="del_pef_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.tlc') }}
                    </h5>
                </label>
                <div>
                    <input id="tlc" class="form-control" name="tlc" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.tlc_pre') }}
                    </h5>
                </label>
                <div>
                    <input id="tlc_pre" class="form-control" name="tlc_pre" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.tlc_pre_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="tlc_pre_pro" class="form-control" name="tlc_pre_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.tlc_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="tlc_pro" class="form-control" name="tlc_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.frc_pre') }}
                    </h5>
                </label>
                <div>
                    <input id="frc_pre" class="form-control" name="frc_pre" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.frc_pre_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="frc_pre_pro" class="form-control" name="frc_pre_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.rv') }}
                    </h5>
                </label>
                <div>
                    <input id="rv" class="form-control" name="rv" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.rv_pre') }}
                    </h5>
                </label>
                <div>
                    <input id="rv_pre" class="form-control" name="rv_pre" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.rv_pre_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="rv_pre_pro" class="form-control" name="rv_pre_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.rv_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="rv_pro" class="form-control" name="rv_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.rv_tlc') }}
                    </h5>
                </label>
                <div>
                    <input id="rv_tlc" class="form-control" name="rv_tlc" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.dlco_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="dlco_pro" class="form-control" name="dlco_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.kco_pro') }}
                    </h5>
                </label>
                <div>
                    <input id="kco_pro" class="form-control" name="kco_pro" type="number" step="0.01" data-parsley-min="0"/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.mmrc') }}
                    </h5>
                </label>
                <div>
                    <select  style="width:100%" class="mmrc form-control" id="mmrc" name="mmrc" >
                        <option value=""></option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
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
