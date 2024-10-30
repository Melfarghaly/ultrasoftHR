<div class="pos-tab-content">
    <div class="row">
        
        <div class="form-group col-md-3">
            <label for="start_date">التاريخ</label>
            <input type="text" name="start_date" id="daily_filter_date_range" class="form-control " >
        </div>
        <div class="col-md-4">
            <div class="form-group ">
                <label for="employee_id">الموظف</label><br>
                <select name="employee_id" id="daily_employee_id" class="form-control select2">
                    @foreach($employees as $row)
                        <option value="{{$row->id}}">{{$row->name_ar}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="mass_posted">Posted</button>
        <button type="button" class="btn btn-success" id="mass_approved">Approved</button>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="daily_timesheet" style="width:100%">
                        <thead>
                            <tr>
                            <th><input type="checkbox" id="select-all-row" data-table-id="daily_timesheet"></th>

                                <th>@lang('messages.date')</th>
                                <th>name</th>
                                <th>timesheet status</th>
                                <th>overtime</th>
                                <th>send</th>
                                <th>approved</th>
                                <th>posted</th>
                                <th>after posted</th>
                                <th>status</th>
                            </tr>
                        </thead>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>