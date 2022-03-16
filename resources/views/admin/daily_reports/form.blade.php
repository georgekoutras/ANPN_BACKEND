<div class="row mB-40">
    <div class="col-sm-8">
        <div class="bgc-white p-20 bd">
            @if ($formAction == 'create')
                <input type="hidden" name="_method" value="POST" />
            @endif
            <input type="hidden" name="patient_id" value="{{ $patientId }}">

            @foreach ($questions as $q)

                <div class="form-group {{ $q->label}}-group" @if(!is_null($q->parent_id))hidden style="padding-left: 20px;" @endif>
                    <label>
                        <h4>
                            {{ $q->text}}
                        </h4>
                    </label>
                    @if($q->type == 'check')
                        <div class="radiobox-container" >
                            {{ trans('app.yes') }} <input type="radio" class="flat {{ $q->label}}" id="{{ $q->label}}_yes" name="{{ $q->label}}" value="1">
                            {{ trans('app.no') }} <input type="radio" class="flat {{ $q->label}}" id="{{ $q->label}}_no" name="{{ $q->label}}" value="0" checked="checked">
                        </div>
                    @elseif($q->type == 'radio')
                        <div class="radiobox-container" >
                            {{ trans('app.ok') }} <input type="radio" class="flat {{ $q->label}}" id="{{ $q->label}}_yes" name="{{ $q->label}}" value="1">
                            {{ trans('app.not_ok') }} <input type="radio" class="flat {{ $q->label}}" id="{{ $q->label}}_no" name="{{ $q->label}}" value="0" checked="checked">
                        </div>
                    @endif
                </div>
            @endforeach
                <label>
                    <h3>
                        <u>{{ trans('app.additional_information') }}</u>
                    </h3>
                </label>
                <div class="form-group">
                    <label>
                        <h4>
                            {{ trans('app.walking_distance') }}
                        </h4>
                    </label>
                    <div>
                        <input id="address" class="form-control" name="walkingDist" type="number" step="0.01" data-parsley-min="0"/>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{ trans('app.heart_rate') }}
                        </h4>
                    </label>
                    <div>
                        <input id="heartRate" class="form-control" name="heartRate" type="number" step="0.01" data-parsley-min="0"/>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{ trans('app.pefr') }}
                        </h4>
                    </label>
                    <div>
                        <input id="pefr" class="form-control" name="pefr" type="number" step="0.01" data-parsley-min="0"/>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{ trans('app.sato2') }}
                        </h4>
                    </label>
                    <div>
                        <input id="satO2" class="form-control" name="satO2" type="number" step="0.01" data-parsley-min="0" data-parsley-max="100" />
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h4>
                            {{ trans('app.temperature') }}
                        </h4>
                    </label>
                    <div>
                        <input id="temperature" class="form-control" name="temperature" type="number" step="0.01" data-parsley-min="0"/>
                    </div>
                </div>
        </div>
    </div>

</div>
