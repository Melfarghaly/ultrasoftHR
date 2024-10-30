<script type="text/javascript">
$(document).ready( function(){
    $("select.accounts-dropdown").select2({
        ajax: {
            url: '{{route("accounts-dropdown")}}',
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                }
            },
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            return data.html;
        },
        templateSelection: function(data) {
            return data.text;
        }
    });
});
$(document).on('mouseover', '.select2-selection__rendered', function(){
    $(this).removeAttr('title');
});
$(document).on('shown.bs.modal', '.modal', function(){
    $(this).find('select.accounts-dropdown').select2({
        dropdownParent: $(this),
        ajax: {
            url: '{{route("accounts-dropdown")}}',
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data
                }
            },
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            return data.html;
        },
        templateSelection: function(data) {
            return data.text;
        }
    });
});
</script>
<script>
  $('#exampleModal_btn').click(function(){

  $('#exampleModal').modal('show');  
  })
  $('.exampleModal_hide').click(function(){

  $('#exampleModal').modal('hide');  
  })
  function post_balance(account_id = 0) {
    var year_from = $('#year_from').val();
    var year_to = $('#year_to').val();
    
    if (year_from.length == 4 && year_to.length == 4) {
        swal({
            title: LANG.confirm_title,
            text: LANG.confirm_text.replace(':year_from', year_from).replace(':year_to', year_to),
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                swal({
                    title: LANG.success_title, // Added title for the success alert
                    text: LANG.success_text,
                    icon: "success",
                });
                var url = base_path + '/accounting/post-balance/' + account_id + '?year_from=' + year_from + '&year_to=' + year_to;
                window.location = url;
            } else {
                swal({
                    title: LANG.cancel_title, // Added title for the cancel alert
                    text: LANG.cancel_text,
                });
            }
        });
    } else {
        swal({
            title: LANG.error_title,
            text: LANG.error_text,
            icon: "error",
            buttons: true,
            dangerMode: true,
        });
    }
}


</script>