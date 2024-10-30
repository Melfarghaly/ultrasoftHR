@extends('layouts.app')

@section('title', __('قائمة الدخل'))

@section('content')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
@endsection
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>

@include('accounting::layouts.nav')
<style>
    .table td {
            border:1px solid black;
            border: 1px solid black;
            font-size: 14px;
            font-weight: bold;
        }
    
    .total_row td{
        background:yellow;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'قائمة الدخل ' )</h1>
</section>
<section class="content">
    <div class="row mb-12">
       
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-solid'])
            
              
                <div id="tabular_vi" class="">
                    <div class="row no-print ">
                        <div class="col-md-12">
                            
                           
                        </div>
                    </div>
                    <div class="row">
                        <button id="print-btn"class=" no-print btn btn-xs btn-warning">Print<i class="fa fa-print"></i></button>
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="table-responsive" id="accounts_tae ">
                                <table class="table" id="table_incomelist">
                                    <thead>
                                        <tr>
                                            <th colspan="3">
                                                <center>
                                                    {{ session('business.name') }}
                                                </center>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>قائمة الدخل عن الفترة 
                                            
                                            </td>
                                            <td>من: <input type="date" class="form-control"></td>
                                            <td>الي : <input type="date" class="form-control"></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>المبيعات</td>
                                            <td> <input class="form-control input_number" type="text" id="B2"> </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>صافي المبيعات</td>
                                            <td></td>
                                            <td> <input class="form-control input_number" type="text"  readonly  id="C3"> </td>
                                        </tr>
                                        <tr>
                                            <td>المشتريات</td>
                                            <td> <input class="form-control input_number" type="text" id="B4"> </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>تكلفة المبيعات </td>
                                            <td></td>
                                            <td> <input class="form-control input_number" type="text"  readonly id="C5"> </td>
                                        </tr>
                                        <tr class="total_row">
                                            <td>مجمل الربح  </td>
                                            <td></td>
                                            <td> <input class="form-control input_number" type="text" readonly  id="C6"> </td>
                                        </tr>
                                         <tr class="">
                                            <td> المصروفات  </td>
                                            <td></td>
                                            <td> </td>
                                        </tr>
                                        <tr>
                                            <td>مصروفات مبيعات وتسويق</td>
                                            <td> <input class="form-control input_number" type="text" id="B8"> </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>   مصروفات ادارية وعمومية</td>
                                            <td> <input class="form-control input_number" type="text" id="B9"> </td>
                                            <td></td>
                                        </tr>
                                        <tr class="">
                                            <td> اجمالي المصروفات   </td>
                                            <td></td>
                                            <td> <input class="form-control input_number" type="text" readonly  id="C10"> </td>
                                        </tr>
                                        <tr class="total_row">
                                            <td> صافي الدخل من الاعمال    </td>
                                            <td></td>
                                            <td> <input class="form-control input_number" type="text"  readonly id="C11"> </td>
                                        </tr>
                                         <tr>
                                            <td>   مصروفات اخري  </td>
                                            <td> <input class="form-control input_number" type="text" id="B12"> </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>   الاجمالي   </td>
                                            <td></td>
                                            <td> <input class="form-control input_number" type="text" readonly  id="C13"> </td>
                                        </tr>
                                        <tr>
                                            <td>      </td>
                                            <td></td>
                                            <td> </td>
                                        </tr>
                                        <tr>
                                            <td>   ايرادات اخري  </td>
                                            <td> <input class="form-control input_number" type="text" id="B15"> </td>
                                            <td></td>
                                        </tr>
                                         <tr>
                                            <td>   الاجمالي   </td>
                                            <td></td>
                                            <td> <input class="form-control input_number" type="text"  readonly id="C16"> </td>
                                        </tr>
                                        <tr class="total_row">
                                            <td>   الدخل قبل الضريبة    </td>
                                            <td></td>
                                            <td> <input class="form-control input_number" type="text"  readonly id="C17"> </td>
                                        </tr>
                                        <tr>
                                            <td>     الضريبة    </td>
                                            <td><input class="form-control input_number" type="text"   id="B18"></td>
                                            <td> <input class="form-control input_number" type="text" readonly id="C18"> </td>
                                        </tr>
                                        <tr>
                                            <td>     صافي الدخل    </td>
                                            <td></td>
                                            <td> <input class="form-control input_number" type="text"  readonly  id="C19"> </td>
                                        </tr>
                                    </tbody>
                                    
                                </table>
                            </div>
                        <div class="col-md-2"></div>
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
  window.print();
}); 
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
	$(document).on('change', '.input_number', function(){
       update_table_data();
    });
    function update_table_data(){
        debugger;
        var B2=__read_number($('input#B2'));
        var C3=B2;
        __write_number($('input#C3'),B2);
      
        var B4=__read_number($('input#B4'));
        var C5=B4;
        __write_number($('input#C5'),B4);
        
        var C6= C3 - C5;
        __write_number($('input#C6'),C6);
         
        var B8=__read_number($('input#B8'));
        var B9=__read_number($('input#B9'));
        var C10=B8+B9;
        __write_number($('input#C10'),C10);
        
        var C11=C6-C10;
        __write_number($('input#C11'),C11);
        
        var B12=__read_number($('input#B12'));
        var C13=B12;
        __write_number($('input#C13'),C13);
        
        var B15=__read_number($('input#B15'));
        var C16=B15;
        __write_number($('input#C16'),C16);
        
        
        
        var C17=C11-C13+C16;
        __write_number($('input#C17'),C17);
        
        var B18=__read_number($('input#B18'));
        var C18=B18 / 100 * C17;
         __write_number($('input#C18'),C18);
         
         var C19=C17-C18;
          __write_number($('input#C19'),C19);
         
    }
    $(document).on('change', '#account_type_filter, #status_filter', function(){
        load_accounts_table();
    });

   

    function load_accounts_tablea(view_type='table'){
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
            url: '/accounting/reports/income-list',
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
  var table = document.getElementById('table_incomelist');

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