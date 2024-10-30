<div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/accounting/edit-account-transaction/update" method="POST">
          @csrf
          <input type="hidden" name="id" value="{{$trans->id}}">
      <div class="modal-body">
        <div class="form-group">
            <label>تعديل</label>
            <input type="text" class="form-control input_number"  name="amount" value="{{$trans->amount}}">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">حفظ </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
      </div>
      </form>
    </div>
  </div>