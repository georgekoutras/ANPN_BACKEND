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
                    <input type="text" class="form-control" id="diagnose_date" name="diagnose_date" data-parsley-required="true" readonly/>
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
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.ltot')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="ltot_yes" name="ltot" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="ltot_no" name="ltot" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.ltot_device') }}
                    </h5>
                </label>
                <div>
                    <select  style="width:100%" class="status form-control"  id="ltot_device" name="ltot_device" >
                        <option value="none">{{ trans('app.none') }}</option>
                        <option value="Cylinder">{{ trans('app.cylinder') }}</option>
                        <option value="Liquid">{{ trans('app.liquid') }}</option>
                        <option value="Concentrator">{{ trans('app.concentrator') }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.ltot_start_date') }}
                    </h5>
                </label>
                <div class="inner-addon right-addon">
                    <i class="glyphicon ti-calendar"></i>
                    <input type="text" class="form-control" id="ltot_start_date" name="ltot_start_date" readonly/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.ventilation_device') }}
                    </h5>
                </label>
                <div>
                    <select  style="width:100%"  class="status form-control"  id="ventilation_device" name="ventilation_device" >
                        <option value="none">{{ trans('app.none') }}</option>
                        <option value="BiPAP">BiPAP</option>
                        <option value="CPAP">CPAP</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h5>
                        {{ trans('app.ventilation_start_date') }}
                    </h5>
                </label>
                <div class="inner-addon right-addon">
                    <i class="glyphicon ti-calendar"></i>
                    <input type="text" class="form-control" id="ventilation_start_date" name="ventilation_start_date" readonly/>
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.antibiotics')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="antibiotics_yes" name="antibiotics" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="antibiotics_no" name="antibiotics" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.antiflu')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="antiflu_yes" name="antiflu" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="antiflu_no" name="antiflu" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.antipneum')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="antipneum_yes" name="antipneum" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="antipneum_no" name="antipneum" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.sama')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="sama_yes" name="sama" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="sama_no" name="sama" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.lama')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="lama_yes" name="lama" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="lama_no" name="lama" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.mycolytocis')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="mycolytocis_yes" name="mycolytocis" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="mycolytocis_no" name="mycolytocis" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.niv')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="niv_yes" name="niv" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="niv_no" name="niv" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.pdef4_inhalator')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="pdef4_inhalator_yes" name="pdef4_inhalator" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="pdef4_inhalator_no" name="pdef4_inhalator" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.theophyline')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="theophyline_yes" name="theophyline" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="theophyline_no" name="theophyline" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.steroids_oral')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="steroids_oral_yes" name="steroids_oral" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="steroids_oral_no" name="steroids_oral" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.steroids_inhaled')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="steroids_inhaled_yes" name="steroids_inhaled" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="steroids_inhaled_no" name="steroids_inhaled" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.short_acting_b2')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="short_acting_b2_yes" name="short_acting_b2" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="short_acting_b2_no" name="short_acting_b2" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.long_acting_b2')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="long_acting_b2_yes" name="long_acting_b2" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="long_acting_b2_no" name="long_acting_b2" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group " >
                <label>
                    <h4>
                        {{ trans('app.ultra_long_acting_b2')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat " id="ultra_long_acting_b2_yes" name="ultra_long_acting_b2" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat " id="ultra_long_acting_b2_no" name="ultra_long_acting_b2" value="0" checked="checked">
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
