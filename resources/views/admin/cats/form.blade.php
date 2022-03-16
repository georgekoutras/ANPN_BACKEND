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
                            {{ trans('app.total_cat_scale') }}
                        </h5>
                    </label>
                    <div>
                        <input id="total_cat_scale" class="form-control" name="total_cat_scale" disabled style="font-weight: bold"/>
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
            @foreach ($questions as $q)

                <div class="form-group {{ $q->label}}-group" @if(!is_null($q->parent_id))hidden style="padding-left: 20px;" @endif>
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
                        @for($i=0; $i<6; $i++)
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
