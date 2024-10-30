@extends('layouts.app')

@section('title', __('الميزانية '))

@section('content')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
@endsection
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>

@include('accounting::layouts.nav')
<style>
    .bg-gray{
        background:#357ca5 !important;   
        color:white !important;   
    }
@media print {
    .bg-gray {
        background: #357ca5 !important;
        color: white !important;
    }
} 
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( ' الميزانية ' )</h1>
</section>
<section class="content">
    <div class="row mb-12">
       
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-solid'])
            
              
                <div id="tabular_view" class="">
                    <div class="row no-print ">
                        <div class="col-md-12">
                            @component('components.filters', ['title' => __('report.filters')])
                                <div class="col-md-4 hide">
                                    <div class="form-group">
                                        {!! Form::label('account_type_filter', __( 'accounting::lang.account_type' ) . ':') !!}
                                        {!! Form::select('account_type_filter', $account_types, null,
                                            ['class' => 'form-control select2', 'style' => 'width:100%', 
                                            'id' => 'account_type_filter', 'placeholder' => __('lang_v1.all')]); !!}
                                    </div>
                                </div>
                                <div class="col-md-4 ">
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
                    <div class="row">
                        <button id="print-btn"class=" no-print btn btn-xs btn-warning">Print<i class="fa fa-print"></i></button>
                        <button id="export-btn"class=" no-print btn btn-xs btn-primary">Export to Excel <i class="fa fa-arrow-up"></i></button>
            
                        <div class="col-md-12" id="accounts_table">
                            
                        
                    </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script type="text/javascript">
document.getElementById('print-btn').addEventListener('click', function () {
   printTable();
}); 
function printTable() {
  let printWindow = window.open('', '_blank');
  let tableContent = document.getElementById('parent_table').outerHTML;

  printWindow.document.write('<html><head><title>Print Table</title>');
  printWindow.document.write("<style>td{border-bottom:0.5px solid #ccc;} .bg-gray td{background:#357ca5 !important;color:white !important; font-weight:bold} .border-td{border:1px solid black !important}</style>"); // Link to your custom print styles
  printWindow.document.write('</head><body dir="rtl">'); // Set direction to RTL
  printWindow.document.write(tableContent);
  printWindow.document.write('</body></html>');

  printWindow.document.close();
  printWindow.print();
}


	$(document).ready( function(){
	    var Cusotmranges = {};
	    Cusotmranges[LANG.this_year] = [moment().startOf('year'), moment().endOf('year')];
        Cusotmranges[LANG.last_year] = [
            moment().startOf('year').subtract(1, 'year'), 
            moment().endOf('year').subtract(1, 'year') 
        ];
        var c_dateRangeSettings = {
                ranges: Cusotmranges,
                startDate: financial_year.start,
                endDate: financial_year.end,
                locale: {
                    cancelLabel: LANG.clear,
                    applyLabel: LANG.apply,
                    customRangeLabel: LANG.custom_range,
                    format: moment_date_format,
                    toLabel: '~',
                },
            };

	    //dateRangeSettings.startDate = moment().subtract(6, 'days');
        //dateRangeSettings.endDate = moment();
        $('#transaction_date_range').daterangepicker(
            c_dateRangeSettings,
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

   

    function load_accounts_table(view_type='table'){
        var data = {view_type: view_type};

        if($('#account_type_filter').val()!== ''){
            data.account_type = $('#account_type_filter').val();
        }
        if($('#status_filter').val()!== ''){
            data.status = $('#status_filter').val();
        }
        if($('#transaction_date_range').val()) {
            var start = $('#transaction_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var end = $('#transaction_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
            data.start_date = start;
            data.end_date = end;
           
        }
        $.ajax({
            url: '/accounting/reports/budget',
            data: data,
            dataType: 'html',
            success: function(html) {
                if(view_type=='table') {
                    $('#accounts_table').html(html);
                } else {
                    $('#accounts_tree').html(html);

                    $.jstree.defaults.core.themes.variant = "large";
                    $('#accounts_tree_container').jstree({
                        "core" : {
                            "themes" : {
                                "responsive": true
                            }
                        },
                        "types" : {
                            "default" : {
                                "icon" : "fa fa-folder"
                            },
                            "file" : {
                                "icon" : "fa fa-file"
                            },
                        },
                        "plugins": ["types", "search"]
                    });

                    var to = false;
                    $('#accounts_tree_search').keyup(function () {
                        if(to) { clearTimeout(to); }
                        to = setTimeout(function () {
                        var v = $('#accounts_tree_search').val();
                        $('#accounts_tree_container').jstree(true).search(v);
                        }, 250);
                    });
                }
                
                 setTimeout(function(){
                    calc_sum_of_table();
                     
                   
                }, 1500);
                 
        }
    })  
    };
    function calc_sum_of_table(){
       debugger;
        var incomeInputs = $('.income');
        // Calculate the sum of the values
        var income = 0;
        incomeInputs.each(function() {
            var value = parseFloat($(this).val());
            if (!isNaN(value)) {
              income += value;
            }
        });
        var expencesInputs = $('.expenses');
        var expenses = 0;
        expencesInputs.each(function() {
            var v = parseFloat($(this).val());
            if (!isNaN(v)) {
              expenses += v;
            }
        });
        totalbt = income + expenses;
        $('span.total_before_tax_txt').text(__currency_trans_from_en(totalbt), true);
        __write_number($('input.total_before_tax'),totalbt);
      
    };
    $(document).on('change', 'input.tax_input', function(e){
        debugger;
        var input_tax=__read_number($('input.tax_input'));
        var total=__read_number($('input.total_before_tax'));
        var tax_amount =  ( total * input_tax / 100) ;
         __write_number($('input.tax_amount_input'),tax_amount);
        var net_total=total - tax_amount;
        __write_number($('input.net_incom'),net_total);
        
        
        $('span.tax_amount_input_txt').text(__currency_trans_from_en(tax_amount), true);
        $('span.net_incom_txt').text(__currency_trans_from_en(net_total), true);
        $('span.input_tax_txt').text(__currency_trans_from_en(input_tax), true);
       $('input.tax_input').fadeOut();
    })
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
document.getElementById('export-btn').addEventListener('click', function () {
  // Get the table element
  var table = document.getElementById('parent_table');

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