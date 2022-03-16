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
                        <h5>
                            {{ trans('app.total_ccq_score') }}
                        </h5>
                    </label>
                    <div>
                        <input id="total_ccq_score" class="form-control" name="total_ccq_score" disabled style="font-weight: bold"/>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h5>
                            {{ trans('app.ccq_symptom_score') }}
                        </h5>
                    </label>
                    <div>
                        <input id="symptom_score" class="form-control" name="symptom_score" disabled style="font-weight: bold"/>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h5>
                            {{ trans('app.ccq_mental_score') }}
                        </h5>
                    </label>
                    <div>
                        <input id="mental_state_score" class="form-control" name="mental_state_score" disabled style="font-weight: bold"/>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <h5>
                            {{ trans('app.ccq_functional_score') }}
                        </h5>
                    </label>
                    <div>
                        <input id="functional_state_score" class="form-control" name="functional_state_score" disabled style="font-weight: bold"/>
                    </div>
                </div>
            @endif

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
            @php
                $title1 = false;
                $title2 = false;
                $title3 = false;
            @endphp
            @foreach ($questions as $q)

                @if($title1 == false && $q->group == 1)
                    <br/>
                    <label>
                        <h3>
                            <u>{{ trans('app.ccq_title1') }}</u>
                        </h3>
                    </label>
                @php
                    $title1 = true;
                @endphp
                @elseif($title2 == false && $q->group == 2)
                    <br/>
                    <label>
                        <h3>
                            <u>{{ trans('app.ccq_title2') }}</u>
                        </h3>
                    </label>
                    @php
                        $title2 = true;
                    @endphp
                @elseif($title3 == false && $q->group == 3)
                    <br/>
                    <label>
                        <h3>
                            <u>{{ trans('app.ccq_title3') }}</u>
                        </h3>
                    </label>
                    @php
                        $title3 = true;
                    @endphp
                @endif
                <div class="form-group {{ $q->label}}-group" style="padding-left: 20px;">
                    <label>
                        <h5>
                            {{ $q->text}}
                        </h5>
                    </label>
                    <br>
                    <div class="row">
                        <label class="label-cat-min label-cat" style="margin-right: 60px">
                            {{ $q->default_min_text}}
                        </label>
                        @for($i=0; $i<7; $i++)
                            <label class="checkbox-inline radio-label" style="margin-right: 30px"><input type="radio" value="{{$i}}" name="{{$q->label}}" id="{{$q->label}}_{{$i}}" @if($i == 0)checked @endif  /><span>{{$i}}</span></label>
                        <!--                            <label class="checkbox-inline radio-label"  style="margin-right: 30px" ><input type="radio" value="{{$i}}" name="{{$q->label}}" @if($i == 0)checked @endif >{{$i}}</label>-->
                        @endfor
                        <label class="label-cat-max label-cat">
                            {{ $q->default_max_text}}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
