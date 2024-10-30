<div class="row">
	<div class="col-md-12">
		@component('components.widget', ['title' => __('essentials::lang.hrm_details')])
			<div class="col-md-4">
				<div class="form-group">
		              {!! Form::label('essentials_department_id', __('essentials::lang.department') . ':') !!}
		              <div class="form-group">
		                  {!! Form::select('essentials_department_id', $departments, !empty($user->essentials_department_id) ? $user->essentials_department_id : null, ['class' => 'form-control select2', 'style' => 'width: 100%;', 'placeholder' => __('messages.please_select') ]); !!}
		              </div>
		          </div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
		            {!! Form::label('essentials_designation_id', __('essentials::lang.designation') . ':') !!}
		            <div class="form-group">
		                {!! Form::select('essentials_designation_id', $designations, !empty($user->essentials_designation_id) ? $user->essentials_designation_id : null, ['class' => 'form-control select2', 'style' => 'width: 100%;', 'placeholder' => __('messages.please_select') ]); !!}
		            </div>
		        </div>
			</div>
		@endcomponent
		@component('components.widget', ['title' => __('essentials::lang.payroll')])
        <div class="row">
            <div class="col-md-4">
                {!! Form::label('location_id', __('lang_v1.primary_work_location') . ':') !!}
                {!! Form::select('location_id', $locations, !empty($user->location_id) ? $user->location_id : null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="multi-input">
                        {!! Form::label('essentials_salary', __('essentials::lang.salary') . ':') !!}
                        <br/>
                        {!! Form::number('essentials_salary', !empty($user->essentials_salary) ? $user->essentials_salary : null, ['class' => 'form-control width-40 pull-left', 'placeholder' => __('essentials::lang.salary')]); !!}
        
                        {!! Form::select('essentials_pay_period', ['month' => __('essentials::lang.per'). ' '.__('lang_v1.month'), 'week' => __('essentials::lang.per'). ' '.__('essentials::lang.week'), 'day' => __('essentials::lang.per'). ' '.__('lang_v1.day')], !empty($user->essentials_pay_period) ? $user->essentials_pay_period : null, ['class' => 'form-control width-60 pull-left']); !!}
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('essentials_pay_cycle', __('essentials::lang.pay_cycle') . ':') !!}
                    <div class="form-group">
                        {!! Form::select('essentials_pay_cycle', ['week' => __('essentials::lang.week'), 'month' => __('lang_v1.month')], !empty($user->essentials_pay_cycle) ? $user->essentials_pay_cycle : null, ['class' => 'form-control select2', 'style' => 'width: 100%;', 'placeholder' => __('messages.please_select') ]); !!}
                    </div>
                </div>
            </div> --}}
            <div class="form-group col-md-4">
                {!! Form::label('pay_components', __('essentials::lang.pay_components') . ':') !!}
                {!! Form::select('pay_components[]', $pay_comoponenets, !empty($allowance_deduction_ids) ? $allowance_deduction_ids : [], ['class' => 'form-control select2', 'multiple' ]); !!}
            </div>
            <div class="col-md-6">
                @php
                $user_id= $user->id ?? 0;
                $employee=0;
                $deduction=DB::table('users')->where('id',$user_id)->first();
                if(!empty($deduction))
                 $fixed_deductions=json_decode(DB::table('users')->where('id',$user_id)->first()->fixed_deductions);
                else
                    $fixed_deductions=null;
                
                @endphp
                <table class="table table-condenced deductions_table" id="deductions_table" data-id="">
                    <thead>
                        <tr>
                            <th class="col-md-5">@lang('essentials::lang.description')</th>
                            <th class="col-md-3">@lang('essentials::lang.amount_type')</th>
                            <th class="col-md-3">@lang('sale.amount')</th>
                            <th class="col-md-1">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(empty($fixed_deductions))
                            @include('essentials::payroll.edit_allowance_and_deduction_row', 
                                ['add_button' => true, 'type' => 'deduction', 'tax'=>true,
                                'name' => __('lang_v1.tax'),
                                'value' => 0,
                                'amount_type' =>  'fixed',
                                'percent' => 0,
                                'disabled'=>true
                            ])
                            @include('essentials::payroll.edit_allowance_and_deduction_row', 
                                ['add_button' => true , 'type' => 'deduction',  'tax'=>true,
                                'name' => __('lang_v1.insurance'),
                                'value' => 0,
                                'amount_type' =>  'fixed',
                                'percent' => 0,
                                'disabled'=>true
                            ])
                        @else
                            @include('essentials::payroll.edit_allowance_and_deduction_row', 
                                ['add_button' => true, 'type' => 'deduction', 'tax'=>true,
                                'name' => $fixed_deductions->deduction_names[0],
                                'value' => $fixed_deductions->deduction_amounts[0],
                                'amount_type' =>  $fixed_deductions->deduction_types[0],
                                'percent' => $fixed_deductions->deduction_percent[0],
                                'disabled'=>true
                            ])
                            @include('essentials::payroll.edit_allowance_and_deduction_row', 
                                ['add_button' => true, 'type' => 'deduction',  'tax'=>true,
                                'name' => $fixed_deductions->deduction_names[1],
                                'value' => $fixed_deductions->deduction_amounts[1],
                                'amount_type' =>  $fixed_deductions->deduction_types[1],
                                'percent' => $fixed_deductions->deduction_percent[1],
                                'disabled'=>true
                            ])

                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @endcomponent
	</div>
</div>
@includeIf('essentials::payroll.form_script')
