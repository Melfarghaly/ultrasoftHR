<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">@lang("accounting::lang.transfer_balance")</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>@lang("accounting::lang.from_year")  </label>
            <input type='text' class="form-control" name="year_from" id="year_from"> 
        </div>
        <div class="form-group">
            <label>@lang("accounting::lang.to_year")  </label>
            <input type='text' class="form-control" name="year_to" id="year_to"> 
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary exampleModal_hide" data-bs-dismiss="modal">@lang("messages.cancel")</button>
        <button type="button" class="btn btn-primary" onClick="post_balance({{$account_id ?? 0 }});">@lang("accounting::lang.transfer")</button>
      </div>
    </div>
  </div>
</div>
