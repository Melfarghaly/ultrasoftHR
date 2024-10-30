<div class="modal-dialog" role="document">
	<div class="modal-content">
		{!! Form::open(['url' => action('\Modules\Repair\Http\Controllers\GuaranteeController@updateStatus', [$guarantee->id]), 'method' => 'put', 'id' => 'update_status_form']) !!}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">@lang( 'repair::lang.edit_status' )</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<strong>
							@lang('repair::lang.job_sheet_no'):
						</strong>
						<span id="job_sheet_no">
							{{$guarantee->id}}
						</span>
					</div>
				</div>
				<div class="row mt-15">
					<div class="col-md-12">
						<div class="form-group">
							{!! Form::label('status_id',  __('sale.status') . ':*') !!}
		            		{!! Form::select('status_id', $status_dropdown['statuses'], $guarantee->status_id, ['class' => 'form-control select2', 'required', 'style' => 'width:100%', 'placeholder' => __('messages.please_select'), 'required', 'id' => 'status_id_modal'], $status_dropdown['template']); !!}
						</div>
					</div>
				</div>
				<div class="row" style="display: none !important;">
					<div class="col-md-12">
						<div class="form-group">
		    				{!! Form::label('update_note', __( 'repair::lang.update_note' ) . ':') !!}
		      				{!! Form::textarea('update_note', null, ['class' => 'form-control', 'placeholder' => __( 'repair::lang.update_note' ), 'rows' => 3, 'id' => 'update_note' ]); !!}
		  				</div>
		  			</div>
		  		</div>
  				<div class="row" style="display: none !important;">
	                <div class="col-md-8">
	                    <div class="form-group">
	                        <div class="checkbox-inline">
	                            <label>
	                                <input type="checkbox" name="send_sms" value="1" id="send_sms"> @lang('repair::lang.send_sms')
	                            </label>
	                        </div>
	                        <div class="checkbox-inline">
	                            <label>
	                                <input type="checkbox" name="send_email" value="1" id="send_email"> @lang('repair::lang.send_email')
	                            </label>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-md-12 sms_body" style="display: none !important;">
	                	<div class="form-group">
	        				{!! Form::label('sms_body', __( 'lang_v1.sms_body' ) . ':') !!}
	          				{!! Form::textarea('sms_body', null, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.sms_body' ), 'rows' => 4, 'id' => 'sms_body']); !!}
	          				<p class="help-block">
		                        <label>{{$status_template_tags['help_text']}}:</label><br>
		                        {{implode(', ', $status_template_tags['tags'])}}
		                    </p>
	      				</div>
	                </div>
	            </div>
	            <div class="row email_template" style="display: none !important;">
	            	<div class="col-md-12">
	            		<div class="form-group">
                    		{!! Form::label('email_subject', __( 'lang_v1.email_subject' ) . ':') !!}
                    		{!! Form::text('email_subject', null, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.email_subject' ), 'id' => 'email_subject']); !!}
                		</div>
            		</div>
             		<div class="col-md-12">
                		<div class="form-group">
                    		{!! Form::label('email_body', __( 'lang_v1.email_body' ) . ':') !!}
                    		{!! Form::textarea('email_body', null, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.email_body' ), 'rows' => 5, 'id' => 'email_body']); !!}
                    		<p class="help-block">
                        		<label>{{$status_template_tags['help_text']}}:</label><br>
                        		{{implode(', ', $status_template_tags['tags'])}}
                    		</p>
                		</div>
            		</div>
	            </div>
			</div>
			<input type="hidden" name="transaction_id" id ="transaction_id" value="{{$transaction_id}}">
			<input type="hidden" name="variation_id_for_retrun" id="variation_id_for_retrun" value="{{$guarantee->variation_id}}">
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary ladda-button">@lang( 'messages.update' )</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
			</div>

		{!! Form::close() !!}
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->