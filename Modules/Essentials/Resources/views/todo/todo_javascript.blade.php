<script type="text/javascript">
	$('#task_modal').on('shown.bs.modal', function() {
	$('form#task_form .datepicker').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
    });
    $('form#task_form .select2').select2({ dropdownParent: $(this) });

	tinymce.init({
        selector: 'textarea#to_do_description',
    });

	 //form validation
	 $("form#task_form").validate();
});

$('#task_modal').on('hide.bs.modal', function(){
	tinymce.remove("textarea#to_do_description");
});

//form submit
$(document).on('submit', 'form#task_form', function(e){
	e.preventDefault();
	var url = $(this).attr("action");
	var method = $(this).attr("method");
	var data = $("form#task_form").serialize();
	var ladda = Ladda.create(document.querySelector('.ladda-button'));
	ladda.start();
	$.ajax({
		method: method,
		url: url,
		data: data,
		dataType: "json",
		success: function(result){
			ladda.stop();
			if(result.success == true){
				$("#task_modal").modal('hide');
				toastr.success(result.msg);
				if (typeof task_table !== 'undefined') {
					task_table.ajax.reload();
				}

				if ($('#calendar').length) {
					$('#calendar').fullCalendar( 'refetchEvents' );
				}
			} else {
				toastr.error(result.msg);
			}
		}
	});
});
</script>