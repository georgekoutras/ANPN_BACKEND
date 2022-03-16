<div class="row mB-40">
    <div class="col-sm-8">
        <div class="bgc-white p-20 bd">
            @if ($formAction == 'create')
                <input type="hidden" name="_method" value="POST" />
            @elseif($formAction == 'edit')
                <input type="hidden" name="_method" value="PUT" />
            @endif
            <input type="hidden" name="patient_id" value="{{ $patientId }}">

            @if ($formAction !== 'create')
                <div class="form-group">
                    <label>
                        <h4>
                            {{ trans('app.totalCharlson') }}
                        </h4>
                    </label>
                    <div>
                        <input id="totalCharlson" class="form-control" name="totalCharlson" disabled style="font-weight: bold"/>
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label>
                    <h4>
                        {{ trans('app.diagnose_date') }} <span class="required">*</span>
                    </h4>
                </label>
                <div class="inner-addon right-addon">
                    <i class="glyphicon ti-calendar"></i>
                    <input type="text" class="form-control" id="diagnose_date" name="diagnose_date" data-parsley-required="true" readonly/>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h4>
                        {{trans('app.myocardialInfarction')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat myocardialInfarction" id="myocardialInfarction_yes" name="myocardialInfarction" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat myocardialInfarction" id="myocardialInfarction_no" name="myocardialInfarction" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h4>
                        {{trans('app.congestiveHeartFailure')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat congestiveHeartFailure" id="congestiveHeartFailure_yes" name="congestiveHeartFailure" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat congestiveHeartFailure" id="congestiveHeartFailure_no" name="congestiveHeartFailure" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h4>
                        {{trans('app.peripheralVascularDisease')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat peripheralVascularDisease" id="peripheralVascularDisease_yes" name="peripheralVascularDisease" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat peripheralVascularDisease" id="peripheralVascularDisease_no" name="peripheralVascularDisease" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h4>
                        {{trans('app.cerebrovascularDisease')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat cerebrovascularDisease" id="cerebrovascularDisease_yes" name="cerebrovascularDisease" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat cerebrovascularDisease" id="cerebrovascularDisease_no" name="cerebrovascularDisease" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h4>
                        {{trans('app.liverDiseaseMild')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat liverDiseaseMild" id="liverDiseaseMild_yes" name="liverDiseaseMild" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat liverDiseaseMild" id="liverDiseaseMild_no" name="liverDiseaseMild" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h4>
                        {{trans('app.diabetes')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat diabetes" id="diabetes_yes" name="diabetes" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat diabetes" id="diabetes_no" name="diabetes" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h4>
                        {{trans('app.dementia')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat dementia" id="dementia_yes" name="dementia" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat dementia" id="dementia_no" name="dementia" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h4>
                        {{trans('app.chronicPulmonaryDisease')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat chronicPulmonaryDisease" id="chronicPulmonaryDisease_yes" name="chronicPulmonaryDisease" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat chronicPulmonaryDisease" id="chronicPulmonaryDisease_no" name="chronicPulmonaryDisease" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h4>
                        {{trans('app.connectiveTissueDisease')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat connectiveTissueDisease" id="connectiveTissueDisease_yes" name="connectiveTissueDisease" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat connectiveTissueDisease" id="connectiveTissueDisease_no" name="connectiveTissueDisease" value="0" checked="checked">
                </div>
            </div>
            <div class="form-group">
                <label>
                    <h4>
                        {{trans('app.ulcerDisease')}}
                    </h4>
                </label>
                <div class="radiobox-container" >
                    {{ trans('app.yes') }} <input type="radio" class="flat ulcerDisease" id="ulcerDisease_yes" name="ulcerDisease" value="1">
                    {{ trans('app.no') }} <input type="radio" class="flat ulcerDisease" id="ulcerDisease_no" name="ulcerDisease" value="0" checked="checked">
                </div>
            </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{trans('app.hemiplegia')}}
                        </h4>
                    </label>
                    <div class="radiobox-container" >
                        {{ trans('app.yes') }} <input type="radio" class="flat hemiplegia" id="hemiplegia_yes" name="hemiplegia" value="1">
                        {{ trans('app.no') }} <input type="radio" class="flat hemiplegia" id="hemiplegia_no" name="hemiplegia" value="0" checked="checked">
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{trans('app.renalDiseaseModerateOrSevere')}}
                        </h4>
                    </label>
                    <div class="radiobox-container" >
                        {{ trans('app.yes') }} <input type="radio" class="flat renalDiseaseModerateOrSevere" id="renalDiseaseModerateOrSevere_yes" name="renalDiseaseModerateOrSevere" value="1">
                        {{ trans('app.no') }} <input type="radio" class="flat renalDiseaseModerateOrSevere" id="renalDiseaseModerateOrSevere_no" name="renalDiseaseModerateOrSevere" value="0" checked="checked">
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{trans('app.diabetesWithEndOrganDamage')}}
                        </h4>
                    </label>
                    <div class="radiobox-container" >
                        {{ trans('app.yes') }} <input type="radio" class="flat diabetesWithEndOrganDamage" id="diabetesWithEndOrganDamage_yes" name="diabetesWithEndOrganDamage" value="1">
                        {{ trans('app.no') }} <input type="radio" class="flat diabetesWithEndOrganDamage" id="diabetesWithEndOrganDamage_no" name="diabetesWithEndOrganDamage" value="0" checked="checked">
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{trans('app.anyTumor')}}
                        </h4>
                    </label>
                    <div class="radiobox-container" >
                        {{ trans('app.yes') }} <input type="radio" class="flat anyTumor" id="anyTumor_yes" name="anyTumor" value="1">
                        {{ trans('app.no') }} <input type="radio" class="flat anyTumor" id="anyTumor_no" name="anyTumor" value="0" checked="checked">
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{trans('app.leukemia')}}
                        </h4>
                    </label>
                    <div class="radiobox-container" >
                        {{ trans('app.yes') }} <input type="radio" class="flat leukemia" id="leukemia_yes" name="leukemia" value="1">
                        {{ trans('app.no') }} <input type="radio" class="flat leukemia" id="leukemia_no" name="leukemia" value="0" checked="checked">
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{trans('app.malignantLymphoma')}}
                        </h4>
                    </label>
                    <div class="radiobox-container" >
                        {{ trans('app.yes') }} <input type="radio" class="flat malignantLymphoma" id="malignantLymphoma_yes" name="malignantLymphoma" value="1">
                        {{ trans('app.no') }} <input type="radio" class="flat malignantLymphoma" id="malignantLymphoma_no" name="malignantLymphoma" value="0" checked="checked">
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{trans('app.liverDiseaseModerateOrSevere')}}
                        </h4>
                    </label>
                    <div class="radiobox-container" >
                        {{ trans('app.yes') }} <input type="radio" class="flat liverDiseaseModerateOrSevere" id="liverDiseaseModerateOrSevere_yes" name="liverDiseaseModerateOrSevere" value="1">
                        {{ trans('app.no') }} <input type="radio" class="flat liverDiseaseModerateOrSevere" id="liverDiseaseModerateOrSevere_no" name="liverDiseaseModerateOrSevere" value="0" checked="checked">
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{trans('app.metastaticSolidMalignancy')}}
                        </h4>
                    </label>
                    <div class="radiobox-container" >
                        {{ trans('app.yes') }} <input type="radio" class="flat metastaticSolidMalignancy" id="metastaticSolidMalignancy_yes" name="metastaticSolidMalignancy" value="1">
                        {{ trans('app.no') }} <input type="radio" class="flat metastaticSolidMalignancy" id="metastaticSolidMalignancy_no" name="metastaticSolidMalignancy" value="0" checked="checked">
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{trans('app.aids')}}
                        </h4>
                    </label>
                    <div class="radiobox-container" >
                        {{ trans('app.yes') }} <input type="radio" class="flat aids" id="aids_yes" name="aids" value="1">
                        {{ trans('app.no') }} <input type="radio" class="flat aids" id="aids_no" name="aids" value="0" checked="checked">
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{trans('app.noConditionAvailable')}}
                        </h4>
                    </label>
                    <div class="radiobox-container" >
                        {{ trans('app.yes') }} <input type="radio" class="flat noConditionAvailable" id="noConditionAvailable_yes" name="noConditionAvailable" value="1">
                        {{ trans('app.no') }} <input type="radio" class="flat noConditionAvailable" id="noConditionAvailable_no" name="noConditionAvailable" value="0" checked="checked">
                    </div>
                </div>

        </div>
    </div>

</div>
