<div class="modal-dialog" role="document">
    <div class="modal-content">
      
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">
                @lang('repair::lang.add_device_model')
            </h4>
        </div>
        <form action="{{ action('\Modules\Repair\Http\Controllers\DeviceModelController@store') }}" method="post" id="device_model">
            @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                   <div class="form-group">
                        {!! Form::label('name', __('repair::lang.model_name') . ':*' )!!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'required' ]) !!}
                   </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                   <div class="form-group">
                        {!! Form::label('brand_id', __('product.brand') .':') !!}
                        {!! Form::select('brand_id', $brands, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'style' => 'width: 100%;', 'id' => 'model_brand_id']); !!}
                   </div>
                </div>
                <div class="col-md-6">
                   <div class="form-group">
                        {!! Form::label('device_id', __('repair::lang.device') .':') !!}
                        {!! Form::select('device_id', $devices, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'style' => 'width: 100%;', 'id' => 'model_device_id']); !!}
                   </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('repair_checklist', __('repair::lang.repair_checklist') . ':') !!} @show_tooltip(__('repair::lang.repair_checklist_tooltip'))
                        {!! Form::textarea('repair_checklist', 'الكاميرا ,البلوتوث, وايفاي ,البطارية ,الشاشة, تاتش ,الشحن,سوفت وير , بصمة ,الشبكة, بيت الخط بيت, الكرت المومري	', ['class' => 'form-control ', 'id' => 'repair_checklist', 'rows' => '3']); !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
                @lang('messages.close')
            </button>
            <button type="submit" class="btn btn-primary add_device_model_btn" id="add_device_model_btn">
                @lang('messages.save')
            </button>
        </div>
        </form>
    </div>
</div>