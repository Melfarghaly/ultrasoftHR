@extends('layouts.app')

@section('title', __('ميزان المراجعة جديد'))

@section('content')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <style>
.lds-hourglass {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
  right:47%;
}
.lds-hourglass:after {
  content: " ";
  display: block;
  border-radius: 50%;
  width: 0;
  height: 0;
  margin: 8px;
  box-sizing: border-box;
  border: 32px solid #21243d;
  border-color: #21243d transparent #21243d transparent;
  animation: lds-hourglass 1.2s infinite;
}
@keyframes lds-hourglass {
  0% {
    transform: rotate(0);
    animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
  }
  50% {
    transform: rotate(900deg);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }
  100% {
    transform: rotate(1800deg);
  }
}

    </style>
    <style>
  #progress-bar-container {
    width: 100%;
    height: 10px;
    background-color: #f0f0f0;
  }

  #progress-bar {
    height: 100%;
    background-color: #4CAF50;
    width: 0%;
  }
</style>

@endsection

@include('accounting::layouts.nav')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'ميزان المراجعة' )</h1>
</section>
<section class="content">
   
    <div class="row mb-12">
        <div class="col-md-12">
           
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-solid'])
            @slot('tool')
                <div class="box-tools">
                    <a class="btn btn-primary pull-right m-5 btn-modal" 
                    href="{{action('\Modules\Accounting\Http\Controllers\CoaController@create')}}" 
                    data-href="{{action('\Modules\Accounting\Http\Controllers\CoaController@create')}}" 
                    data-container="#create_account_modal">
                    <i class="fas fa-plus"></i> @lang( 'messages.add' )</a>
                </div>
            @endslot
              
                <div id="tabular_view" class="">
                    <div class="row no-print ">
                        <div class="col-md-12">
                            @component('components.filters', ['title' => __('report.filters')])
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('account_type_filter', __( 'accounting::lang.account_type' ) . ':') !!}
                                        {!! Form::select('account_type_filter', $account_types, null,
                                            ['class' => 'form-control select2', 'style' => 'width:100%', 
                                            'id' => 'account_type_filter', 'placeholder' => __('lang_v1.all')]); !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('status_filter', __( 'sale.status' ) . ':') !!}
                                        {!! Form::select('status_filter', ['active' => __( 'accounting::lang.active' ),
                                            'inactive' => __('lang_v1.inactive')], null,
                                            ['class' => 'form-control select2', 'style' => 'width:100%', 
                                            'id' => 'status_filter', 'placeholder' => __('lang_v1.all')]); !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {!! Form::label('transaction_date_range', __('report.date_range') . ':') !!}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            {!! Form::text('transaction_date_range', null, ['class' => 'form-control', 'readonly', 'placeholder' => __('report.date_range')]) !!}
                                        </div>
                                    </div>
                                </div>
                            @endcomponent
                        </div>
                    </div>
                     <button id="print-btn"class=" no-print btn btn-xs btn-warning">Print<i class="fa fa-print"></i></button>
                        <button id="export-btn"class=" no-print btn btn-xs btn-primary">Export to Excel <i class="fa fa-arrow-up"></i></button>
                    <div class="table-responsive" id="accounts_table">
                        <div class="lds-hourglass"></div>
                        <center> <h6>قد يستغرق بعض الوقت</h6></center>
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
</section>
<div class="modal fade" id="create_account_modal" tabindex="-1" role="dialog">
</div>
@stop
@section('javascript')
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>


<script type="text/javascript">
 
	$(document).ready( function(){
	    //dateRangeSettings.startDate = moment().subtract(6, 'days');
        //dateRangeSettings.endDate = moment();
        $('#transaction_date_range').daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $('#transaction_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                
               load_accounts_table();
            }
        );
        load_accounts_table();
        load_accounts_table('tree');
	});

    $(document).on('change', '#account_type_filter, #status_filter', function(){
        load_accounts_table();
    });

    $('input[type=radio][name=view_type]').change(function() {
        if (this.value == 'tree') {
            $('#accounts_tree').removeClass('hide');
            $('#tabular_view').addClass('hide');
        }
        else if (this.value == 'table') {
            $('#accounts_tree').addClass('hide');
            $('#tabular_view').removeClass('hide');
        }
    });
    function updateProgressBar(percentage) {
  $('#progress-bar').css('width', percentage + '%');
}

function load_accounts_table(view_type = 'table') {
    var ht='<div class="lds-hourglass"></div><center> <h6>قد يستغرق بعض الوقت</h6></center>';
    $('#accounts_table').html(ht);
   
  var data = { view_type: 'table' };
  if ($('#account_type_filter').val() !== '') {
    data.account_type = $('#account_type_filter').val();
  }
  if ($('#status_filter').val() !== '') {
    data.status = $('#status_filter').val();
  }
  if ($('#transaction_date_range').val()) {
    var start = $('#transaction_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var end = $('#transaction_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
    data.start_date = start;
    data.end_date = end;
  }

  $.ajax({
    url: '/accounting/reports/trial-balance-new',
    data: data,
    dataType: 'html',

    success: function(html) {
    
      if (view_type == 'table') {
        $('#accounts_table').html(html);
      }
      $('#progress-bar-container').hide();
    },
  });
  
}

    $(document).on('click', '#expand_all', function(e){
        $('#accounts_tree_container').jstree("open_all");
    })
    $(document).on('click', '#collapse_all', function(e){
        $('#accounts_tree_container').jstree("close_all");
    })

    $(document).on('shown.bs.modal', '#create_account_modal', function(){
        $(this).find('#account_sub_type').select2({
            dropdownParent: $('#create_account_modal')
        });
        $(this).find('#detail_type').select2({
            dropdownParent: $('#create_account_modal')
        });
        $(this).find('#parent_account').select2({
            dropdownParent: $('#create_account_modal')
        });
        $('#as_of').datepicker({
            autoclose: true,
            endDate: 'today',
        });
        init_tinymce('description');
    });

    $(document).on('hidden.bs.modal', '#create_account_modal', function(){
        tinymce.remove("#description");
    });
    $(document).on('change', '#account_primary_type', function(){
        if($(this).val() !== '') {
            $.ajax({
                url: '/accounting/get-account-sub-types?account_primary_type=' + $(this).val(),
                dataType: 'json',
                success: function(result) {
                    $('#account_sub_type').select2('destroy')
                        .empty()
                        .select2({
                            data: result.sub_types,
                            dropdownParent: $('#create_account_modal'),
                        }).on('change', function() {
                            if($(this).select2('data')[0].show_balance==1) {
                                $('#bal_div').removeClass('hide');
                            } else {
                                $('#bal_div').addClass('hide');
                            }
                        });
                        $('#account_sub_type').change();
                },
            });
        }
    });
    $(document).on('change', '#account_sub_type', function(){
        if($(this).val() !== '') {
            $.ajax({
                url: '/accounting/get-account-details-types?account_type_id=' + $(this).val(),
                dataType: 'json',
                success: function(result) {
                    $('#detail_type').select2('destroy')
                            .empty()
                            .select2({
                                data: result.detail_types,
                                dropdownParent: $('#create_account_modal'),
                            }).on('change', function() {
                                if($(this).val() !== '') {
                                    var desc = $(this).select2('data')[0].description;
                                    $('#detail_type_desc').html(desc);
                                }
                            });
                        $('#parent_account').select2('destroy')
                        .empty()
                        .select2({
                            data: result.parent_accounts,
                            dropdownParent: $('#create_account_modal'),
                        });
                },
            });
        }
    })

    $(document).on('click', 'a.activate-deactivate-btn', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            dataType: 'json',
            success: function(response) {
                toastr.success(response.msg);
                load_accounts_table();
                load_accounts_table('tree');
            },
        });
    })
    $(document).on('click', 'a.ledger-link', function(e) {
        window.location.href = $(this).attr('href');
    });
</script>
<script>
$(document).on('change', '#is_second_currency', function() {
  if ($(this).is(':checked')) {
    $('#second_currency').show();
    $('#currency').prop('required', true);
  } else {
    $('#second_currency').hide();
    $('#currency').prop('required', false);
  }
});
document.getElementById('print-btn').addEventListener('click', function () {
  window.print();
}); 
document.getElementById('export-btn').addEventListener('click', function () {
  // Get the table element
  var table = document.getElementById('trial_new');

  // Prepare the workbook and worksheet
  var wb = XLSX.utils.table_to_book(table);
  var ws = wb.Sheets[wb.SheetNames[0]];

  // Generate a file name
  var fileName = 'exported_data.xlsx';

  // Convert the workbook to an Excel file
  var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

  // Create a Blob from the Excel file data
  var blob = new Blob([s2ab(wbout)], { type: 'application/octet-stream' });

  // Create a download link and trigger the download
  var downloadLink = document.createElement('a');
  downloadLink.href = URL.createObjectURL(blob);
  downloadLink.download = fileName;
  downloadLink.click();
});

// Utility function to convert string to ArrayBuffer
function s2ab(s) {
  var buf = new ArrayBuffer(s.length);
  var view = new Uint8Array(buf);
  for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
  return buf;
}
</script>
@endsection