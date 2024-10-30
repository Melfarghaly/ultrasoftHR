<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-4">
            
        </div>
    </div>

    <div class="row">
        
     
              
                <div class="form-group  col-md-3">
                    <label for="month">Select Month</label>
                    <input type="month" name="month" id="month" class="form-control" required>
                </div>

                <div class="form-group col-md-3">
                    <label for="week">Select Week</label>   
                    <select name="week" id="week" class="form-control" required>
                        <option value="">Select a week</option>
                        <option value="1">First Week</option>
                        <option value="2">Second Week</option>
                        <option value="3">Third Week</option>
                        <option value="4">Fourth Week</option>
                        <option value="5">Fifth Week</option>
                        <option value="6">Sixth Week</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div class="form-group col-md-3">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" readonly>
                </div>

                <!-- End Date -->
                <div class="form-group col-md-3">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" readonly>
                </div>

                <button type="button" class="btn btn-primary" id="mass_attend">حضور</button>
           
      
        <div class="col-md-12">
           
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="weekly_timesheet"  style="width:100%">
                        <thead>
                            <tr>
                               
                            <th><input type="checkbox" id="select-all-row" data-table-id="weekly_timesheet"></th>
                                <th>الرقم الوظيفي</th>
                                <th>الاسم</th>
                                <th>الجنسية</th>
                                <th>المهنة</th>
                                <th>اسم المشروع</th>
                               
                                
                            </tr>
                        </thead>
                    </table>
                    <tfoot>
                        <tr> 
                            <td>
                            <div style="display: flex; width: 100%;">
                               
                            </div>
                            </td>
                        </tr>
                    </tfoot>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>