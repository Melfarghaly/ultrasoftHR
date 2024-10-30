
<div class="pos-tab-content">
    <div class="box box-solid">
    <form action="{{action('\Modules\Essentials\Http\Controllers\ToDoController@store_status')}}" method="POST" class="row" id="meeting_status_form">
            @csrf
            <div class="form-group col-md-4">
                {!! Form::label('stock_expiry_alert_days', __('نوع   ') . ':*') !!}
                <div class="input-group"  style="width: 100%;">
                {!! Form::text('name', null, ['id'=>'status_name','class' => 'form-control']); !!}
                </div>
            </div>

            <div class="form-group col-md-2">
                <br>
                <input class="btn btn-primary" type='button' value="حفظ" id="meeting_status">
            </div>
            <div class="col-md-2"></div>
    </form>
<div class="row">
    <div class="col-md-3">
        <input id="txt_searchall" class="form-control" value="" type="text" placeholder="بحث">
    </div>
</div>
</div>
    <div class="box box-primary">
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table table-striped" >
                        <thead class="thead-dark">
                            <tr style="background: black;color: white;">
                                <td>النوع </td>
                                
                                <td>حذف</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                             $business_id = request()->session()->get('user.business_id');
                            $statuses=\DB::table('meeting_status')->where('business_id',$business_id)->get();
                            @endphp
                            @foreach($statuses as $row)
                            <tr>
                                <td>{{$row->name}}</td>
                                
                                <td><a class="btn btn-danger" href="/essentials/todo/meeting_status/{{$row->id}}" ><i class="fa fa-trash"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




<script>


$("#meeting_status").click(function(event) {
    debugger;
    //event.preventDefault(); 
    name=$("#status_name").val();
     $.ajax({
               type:'POST',
               url:"{{action('\Modules\Essentials\Http\Controllers\ToDoController@store_status')}}",
               data:{
                   '_token' :"<?php echo csrf_token() ?>",
                   'name':name
               },
               success:function(data) {
                    toastr.success(data.msg);
                 
               }
            });
    
    location.reload();
});
$(document).ready(function(){

  // Search all columns
  $('#txt_searchall').keyup(function(){
    // Search Text
    var search = $(this).val();

    // Hide all table tbody rows
    $('table tbody tr').hide();

    // Count total search result
    var len = $('table tbody tr:not(.notfound) td:contains("'+search+'")').length;

    if(len > 0){
      // Searching text in columns and show match row
      $('table tbody tr:not(.notfound) td:contains("'+search+'")').each(function(){
        $(this).closest('tr').show();
      });
    }else{
      $('.notfound').show();
    }

  });

  // Search on name column only
  $('#txt_name').keyup(function(){
    // Search Text
    var search = $(this).val();

    // Hide all table tbody rows
    $('table tbody tr').hide();

    // Count total search result
    var len = $('table tbody tr:not(.notfound) td:nth-child(2):contains("'+search+'")').length;

    if(len > 0){
      // Searching text in columns and show match row
      $('table tbody tr:not(.notfound) td:contains("'+search+'")').each(function(){
         $(this).closest('tr').show();
      });
    }else{
      $('.notfound').show();
    }

  });

});

// Case-insensitive searching (Note - remove the below script for Case sensitive search )
$.expr[":"].contains = $.expr.createPseudo(function(arg) {
   return function( elem ) {
     return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
   };
});
</script>
