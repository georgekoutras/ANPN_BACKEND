
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });
initSelect2 = function (objectIdentifier, placeholder, params){

    var minimumInputLength;
    if(_.isUndefined(params.minimumInputLength))
    {
        minimumInputLength = -1;
    }
    else
    {
        minimumInputLength = params.minimumInputLength;
    }

    $(objectIdentifier).select2({
        "placeholder":placeholder,
        "width": _.isUndefined(params.width) ? "resolve" : params.width,
        "minimumInputLength": minimumInputLength,
        //"allowClear": _.isUndefined(params.allowClear) ? true : params.allowClear,
        "allowClear":false,
        //"language": select2Locale,
        "disabled": _.isUndefined(params.disabled) ? false : params.disabled,
        "multiple":params.multiple,
        "dataAdapter": !_.isUndefined(params.dataAdapter) ? params.dataAdapter : null,
        "defaultResults": !_.isUndefined(params.defaultResults) ? params.defaultResults : null,
        "minimumResultsForSearch": _.isUndefined(params.minimumResultsForSearch) ? 0 : params.minimumResultsForSearch,
        "ajax": {
            beforeSend: function(xhr)
            {
                xhr.setRequestHeader('Access-Token', window.access_token);
            },
            url:params.ajax.url,
            data:params.ajax.data,
            processResults:params.ajax.processResults,
            dataType: 'json',
            error:function(jqXHR,data)
            {
                if(jqXHR.status === 498)
                {
                    refreshToken(null, objectIdentifier);
                }
            },
            cache: false,
        },
        escapeMarkup: _.isUndefined(params.escapeMarkup) ? $.fn.select2.defaults.defaults.escapeMarkup : params.escapeMarkup,

    });
    if (_.isUndefined(params.allowClear) || params.allowClear === true){
        $(objectIdentifier).on('select2:unselecting', function(){
            $(this).data('state', 'unselected');
        }).on('select2:open', function(e){
            if ($(this).data('state') === 'unselected') {
                $(this).removeData('state');

                var self = $(this);
                setTimeout(function() {
                    self.select2('close');
                }, 1);
            }
        });
    }
}

fillInAccount = function (data, object, role) {

    object.find("[name='first_name']").val(data.first_name);
    object.find("[name='last_name']").val(data.last_name);
    if (data.second_name) {
        object.find("[name='second_name']").val(data.second_name);
    }
    object.find("[name='email']").val(data.email);
    object.find("[name='mobile']").val(data.mobile);
    if (data.patient_info) {
        $('#file_id').prop('disabled',true);

        object.find("[name='social_id']").val(data.patient_info.social_id);
        object.find("[name='land_line']").val(data.patient_info.land_line);
        object.find("[name='file_id']").val(data.patient_info.file_id);
        object.find("[name='first_diagnose_date']").val(data.patient_info.first_diagnose_date);
        object.find("[name='birth_date']").val(data.patient_info.birth_date);
        object.find("[name='address']").val(data.patient_info.address);
        if(role === 'administrator') {
            if (document.getElementById('doctor_id').type !== 'hidden') {
                $("#doctor_id").empty().append('<option selected value="' + data.patient_info.doctor.id + '">' + data.patient_info.doctor.last_name + " " + data.patient_info.doctor.first_name + '</option>');
                $("#doctor_id").select2('data', {
                    id: data.patient_info.doctor.id,
                    label: data.patient_info.doctor.last_name + " " + data.patient_info.doctor.first_name
                });
            }
        }else {
            object.find("[name='doctor_id']").val(data.patient_info.doctor.id);
        }
        object.find("[name='sex']").val(data.patient_info.sex);

    }
    if (data.notification_enabled) {
        $("#notification_enabled_yes").prop("checked", true);
        $('.notification-mode').removeAttr('hidden');
    } else {
        $("#notification_enabled_no").prop("checked", true);
    }
    object.find("[name='notification_mode']").val(data.notification_mode);

}

fillInDailyReport = function (data, object) {

    object.find($('input')).prop('readonly', true);
    object.find($('input')).prop('disabled', true);
    object.find($('textarea')).prop('readonly', true);
    object.find($('textarea')).prop('disabled', true);
    object.find('select').each(function(){
        $(this).prop('disabled', true);
    });

    if (data.q1) {
        object.find("#q1_yes").prop("checked", true);
        if(data.q1a){
            object.find("#q1a_yes").prop("checked", true);
        }
        if(data.q1b){
            object.find("#q1b_yes").prop("checked", true);
        }
        if(data.q1c){
            object.find("#q1c_yes").prop("checked", true);
        }
        object.find('.q1a-group').removeAttr('hidden');
        object.find('.q1b-group').removeAttr('hidden');
        object.find('.q1c-group').removeAttr('hidden');
    } else {
        object.find("#q1_no").prop("checked", true);
    }
    if (data.q2) {
        object.find("#q2_yes").prop("checked", true);
    } else {
        object.find("#q2_no").prop("checked", true);
    }
    if (data.q3) {
        object.find("#q3_yes").prop("checked", true);
        if(data.q3a){
            object.find("#q3a_yes").prop("checked", true);
        }
        if(data.q3b){
            object.find("#q3b_yes").prop("checked", true);
        }
        if(data.q3c){
            object.find("#q3c_yes").prop("checked", true);
        }
        object.find('.q3a-group').removeAttr('hidden');
        object.find('.q3b-group').removeAttr('hidden');
        object.find('.q3c-group').removeAttr('hidden');
    } else {
        object.find("#q3_no").prop("checked", true);
    }
    if (data.q4) {
        object.find("#q4_yes").prop("checked", true);
    } else {
        object.find("#q4_no").prop("checked", true);
    }
    if (data.q5) {
        object.find("#q5_yes").prop("checked", true);
    } else {
        object.find("#q5_no").prop("checked", true);
    }

    object.find("[name='walkingDist']").val(data.walkingDist);
    object.find("[name='temperature']").val(data.temperature);
    object.find("[name='pefr']").val(data.pefr);
    object.find("[name='satO2']").val(data.satO2);
    object.find("[name='heartRate']").val(data.heartRate);

}

fillInCat = function (data, object) {

    object.find("[name='total_cat_scale']").val(data.total_cat_scale);
    object.find("[name='diagnose_date']").val(data.diagnose_date);
    object.find("[name='status']").val(data.status).trigger('change');
    object.find("#q1_"+data.q1).prop("checked", true);
    object.find("#q2_"+data.q2).prop("checked", true);
    object.find("#q3_"+data.q3).prop("checked", true);
    object.find("#q4_"+data.q4).prop("checked", true);
    object.find("#q5_"+data.q5).prop("checked", true);
    object.find("#q6_"+data.q6).prop("checked", true);
    object.find("#q7_"+data.q7).prop("checked", true);
    object.find("#q8_"+data.q8).prop("checked", true);

}

fillInCcq = function (data, object) {

    object.find("[name='total_ccq_score']").val(data.total_ccq_score);
    object.find("[name='symptom_score']").val(data.symptom_score);
    object.find("[name='mental_state_score']").val(data.mental_state_score);
    object.find("[name='functional_state_score']").val(data.functional_state_score);

    object.find("[name='diagnose_date']").val(data.diagnose_date);
    object.find("[name='status']").val(data.status).trigger('change');
    object.find("#q1_"+data.q1).prop("checked", true);
    object.find("#q2_"+data.q2).prop("checked", true);
    object.find("#q3_"+data.q3).prop("checked", true);
    object.find("#q4_"+data.q4).prop("checked", true);
    object.find("#q5_"+data.q5).prop("checked", true);
    object.find("#q6_"+data.q6).prop("checked", true);
    object.find("#q7_"+data.q7).prop("checked", true);
    object.find("#q8_"+data.q8).prop("checked", true);
    object.find("#q9_"+data.q8).prop("checked", true);
    object.find("#q10_"+data.q8).prop("checked", true);


}

fillInCci = function (data, object) {

    object.find("[name='totalCharlson']").val(data.totalCharlson);
    object.find("[name='diagnose_date']").val(data.diagnose_date);

    if (data.myocardialInfarction) {
        object.find("#myocardialInfarction_yes").prop("checked", true);
    } else {
        object.find("#myocardialInfarction_no").prop("checked", true);
    }
    if (data.congestiveHeartFailure) {
        object.find("#congestiveHeartFailure_yes").prop("checked", true);
    } else {
        object.find("#congestiveHeartFailure_no").prop("checked", true);
    }
    if (data.peripheralVascularDisease) {
        object.find("#peripheralVascularDisease_yes").prop("checked", true);
    } else {
        object.find("#peripheralVascularDisease_no").prop("checked", true);
    }
    if (data.cerebrovascularDisease) {
        object.find("#cerebrovascularDisease_yes").prop("checked", true);
    } else {
        object.find("#cerebrovascularDisease_no").prop("checked", true);
    }
    if (data.dementia) {
        object.find("#dementia_yes").prop("checked", true);
    } else {
        object.find("#dementia_no").prop("checked", true);
    }
    if (data.chronicPulmonaryDisease) {
        object.find("#chronicPulmonaryDisease_yes").prop("checked", true);
    } else {
        object.find("#chronicPulmonaryDisease_no").prop("checked", true);
    }
    if (data.connectiveTissueDisease) {
        object.find("#connectiveTissueDisease_yes").prop("checked", true);
    } else {
        object.find("#connectiveTissueDisease_no").prop("checked", true);
    }
    if (data.ulcerDisease) {
        object.find("#ulcerDisease_yes").prop("checked", true);
    } else {
        object.find("#ulcerDisease_no").prop("checked", true);
    }
    if (data.liverDiseaseMild) {
        object.find("#liverDiseaseMild_yes").prop("checked", true);
    } else {
        object.find("#liverDiseaseMild_no").prop("checked", true);
    }
    if (data.diabetes) {
        object.find("#diabetes_yes").prop("checked", true);
    } else {
        object.find("#diabetes_no").prop("checked", true);
    }
    if (data.hemiplegia) {
        object.find("#hemiplegia_yes").prop("checked", true);
    } else {
        object.find("#hemiplegia_no").prop("checked", true);
    }
    if (data.renalDiseaseModerateOrSevere) {
        object.find("#renalDiseaseModerateOrSevere_yes").prop("checked", true);
    } else {
        object.find("#renalDiseaseModerateOrSevere_no").prop("checked", true);
    }
    if (data.diabetesWithEndOrganDamage) {
        object.find("#diabetesWithEndOrganDamage_yes").prop("checked", true);
    } else {
        object.find("#diabetesWithEndOrganDamage_no").prop("checked", true);
    }
    if (data.anyTumor) {
        object.find("#anyTumor_yes").prop("checked", true);
    } else {
        object.find("#anyTumor_no").prop("checked", true);
    }
    if (data.leukemia) {
        object.find("#leukemia_yes").prop("checked", true);
    } else {
        object.find("#leukemia_no").prop("checked", true);
    }
    if (data.malignantLymphoma) {
        object.find("#malignantLymphoma_yes").prop("checked", true);
    } else {
        object.find("#malignantLymphoma_no").prop("checked", true);
    }
    if (data.liverDiseaseModerateOrSevere) {
        object.find("#liverDiseaseModerateOrSevere_yes").prop("checked", true);
    } else {
        object.find("#liverDiseaseModerateOrSevere_no").prop("checked", true);
    }
    if (data.metastaticSolidMalignancy) {
        object.find("#metastaticSolidMalignancy_yes").prop("checked", true);
    } else {
        object.find("#metastaticSolidMalignancy_no").prop("checked", true);
    }
    if (data.aids) {
        object.find("#aids_yes").prop("checked", true);
    } else {
        object.find("#aids_no").prop("checked", true);
    }
    if (data.noConditionAvailable) {
        object.find("#noConditionAvailable_yes").prop("checked", true);
    } else {
        object.find("#noConditionAvailable_no").prop("checked", true);
    }

}

fillInReading = function (data, object) {

    object.find("[name='status']").val(data.status).trigger('change');
    object.find("[name='smoker']").val(data.smoker).trigger('change');
    object.find("[name='mmrc']").val(data.mmrc).trigger('change');
    object.find("[name='diagnose_date']").val(data.diagnose_date);
    object.find("[name='notes']").val(data.notes);

    object.find("[name='weight']").val(data.weight);
    object.find("[name='height']").val(data.height);
    object.find("[name='pxy']").val(data.pxy);
    object.find("[name='pao2']").val(data.pao2);
    object.find("[name='paco2']").val(data.paco2);
    object.find("[name='satO2_pro']").val(data.satO2_pro);
    object.find("[name='hco3']").val(data.hco3);
    object.find("[name='ph']").val(data.ph);
    object.find("[name='hematocrit']").val(data.hematocrit);
    object.find("[name='fvc']").val(data.fvc);
    object.find("[name='fvc_pre']").val(data.fvc_pre);
    object.find("[name='fvc_pre_pro']").val(data.fvc_pre_pro);
    object.find("[name='fvc_post']").val(data.fvc_post);
    object.find("[name='fvc_pro']").val(data.fvc_pro);
    object.find("[name='fev1']").val(data.fev1);
    object.find("[name='fev1_pre']").val(data.fev1_pre);
    object.find("[name='fev1_pre_pro']").val(data.fev1_pre_pro);
    object.find("[name='fev1_post']").val(data.fev1_post);
    object.find("[name='fev1_pro']").val(data.fev1_pro);
    object.find("[name='del_fev1_post']").val(data.del_fev1_post);
    object.find("[name='fev1_fvc']").val(data.fev1_fvc);
    object.find("[name='fev1_fvc_pre']").val(data.fev1_fvc_pre);
    object.find("[name='fef25_75_pre_pro']").val(data.fef25_75_pre_pro);
    object.find("[name='del_fef25_75_pro']").val(data.del_fef25_75_pro);
    object.find("[name='pef_pre_pro']").val(data.pef_pre_pro);
    object.find("[name='del_pef_pro']").val(data.del_pef_pro);
    object.find("[name='tlc']").val(data.tlc);
    object.find("[name='tlc_pre']").val(data.tlc_pre);
    object.find("[name='tlc_pre_pro']").val(data.tlc_pre_pro);
    object.find("[name='tlc_pro']").val(data.tlc_pro);
    object.find("[name='frc_pre']").val(data.frc_pre);
    object.find("[name='frc_pre_pro']").val(data.frc_pre_pro);
    object.find("[name='rv']").val(data.rv);
    object.find("[name='rv_pre']").val(data.rv_pre);
    object.find("[name='rv_pre_pro']").val(data.rv_pre_pro);
    object.find("[name='rv_pro']").val(data.rv_pro);
    object.find("[name='rv_tlc']").val(data.rv_tlc);
    object.find("[name='dlco_pro']").val(data.dlco_pro);
    object.find("[name='kco_pro']").val(data.kco_pro);

}

fillInTreatment = function (data, object) {

    object.find("[name='status']").val(data.status).trigger('change');
    object.find("[name='ltot_device']").val(data.ltot_device).trigger('change');
    object.find("[name='ventilation_device']").val(data.ventilation_device).trigger('change');
    object.find("[name='diagnose_date']").val(data.diagnose_date);
    object.find("[name='ltot_start_date']").val(data.ltot_start_date);
    object.find("[name='ventilation_start_date']").val(data.ventilation_start_date);
    object.find("[name='notes']").val(data.notes);

    if (data.ltot) {
        object.find("#ltot_yes").prop("checked", true);
    } else {
        object.find("#ltot_no").prop("checked", true);
    }

    if (data.antibiotics) {
        object.find("#antibiotics_yes").prop("checked", true);
    } else {
        object.find("#antibiotics_no").prop("checked", true);
    }

    if (data.antiflu) {
        object.find("#antiflu_yes").prop("checked", true);
    } else {
        object.find("#antiflu_no").prop("checked", true);
    }

    if (data.antipneum) {
        object.find("#antipneum_yes").prop("checked", true);
    } else {
        object.find("#antipneum_no").prop("checked", true);
    }

    if (data.lama) {
        object.find("#lama_yes").prop("checked", true);
    } else {
        object.find("#lama_no").prop("checked", true);
    }

    if (data.long_acting_b2) {
        object.find("#long_acting_b2_yes").prop("checked", true);
    } else {
        object.find("#long_acting_b2_no").prop("checked", true);
    }

    if (data.mycolytocis) {
        object.find("#mycolytocis_yes").prop("checked", true);
    } else {
        object.find("#mycolytocis_no").prop("checked", true);
    }

    if (data.niv) {
        object.find("#niv_yes").prop("checked", true);
    } else {
        object.find("#niv_no").prop("checked", true);
    }

    if (data.pdef4_inhalator) {
        object.find("#pdef4_inhalator_yes").prop("checked", true);
    } else {
        object.find("#pdef4_inhalator_no").prop("checked", true);
    }

    if (data.sama) {
        object.find("#sama_yes").prop("checked", true);
    } else {
        object.find("#sama_no").prop("checked", true);
    }

    if (data.short_acting_b2) {
        object.find("#short_acting_b2_yes").prop("checked", true);
    } else {
        object.find("#short_acting_b2_no").prop("checked", true);
    }

    if (data.steroids_inhaled) {
        object.find("#steroids_inhaled_yes").prop("checked", true);
    } else {
        object.find("#steroids_inhaled_no").prop("checked", true);
    }

    if (data.steroids_oral) {
        object.find("#steroids_oral_yes").prop("checked", true);
    } else {
        object.find("#steroids_oral_no").prop("checked", true);
    }

    if (data.theophyline) {
        object.find("#theophyline_yes").prop("checked", true);
    } else {
        object.find("#theophyline_no").prop("checked", true);
    }

    if (data.ultra_long_acting_b2) {
        object.find("#ultra_long_acting_b2_yes").prop("checked", true);
    } else {
        object.find("#ultra_long_acting_b2_no").prop("checked", true);
    }
}

fillInDeath = function (data,patientId,patientName, object) {

    object.find("[name='date']").val(data.date);
    object.find("[name='notes']").val(data.notes);

    $("#patient_id").empty().append('<option selected value="' + patientId + '">' + patientName + '</option>');
    $("#patient_id").select2('data', {
        id: patientId,
        label: patientName
    });
    if (data.cardiovascular) {
        object.find("#cardiovascular_yes").prop("checked", true);
    } else {
        object.find("#cardiovascular_no").prop("checked", true);
    }
    if (data.respiratory) {
        object.find("#respiratory_yes").prop("checked", true);
    } else {
        object.find("#respiratory_no").prop("checked", true);
    }
    if (data.infectious_disease) {
        object.find("#infectious_disease_yes").prop("checked", true);
    } else {
        object.find("#infectious_disease_no").prop("checked", true);
    }
    if (data.malignancy) {
        object.find("#malignancy_yes").prop("checked", true);
    } else {
        object.find("#malignancy_no").prop("checked", true);
    }
}

