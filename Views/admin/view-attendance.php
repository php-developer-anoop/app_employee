<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a href="<?= base_url(ADMINPATH . 'dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item active">
              <?= $title; ?>
            </li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="form-group col-lg-2">
          <label for="user_type">Select User Type</label>
          <select name="user_type" id="user_type" class="form-control select2" required onchange="getUsers(this.value,<?=$user_id?>)">
            <option value="">--Select State--</option>
            <option value="employee" <?=!empty($user_type) && ($user_type=="employee")?'selected':''?>>Employee</option>
            <option value="volunteer" <?=!empty($user_type) && ($user_type=="volunteer")?'selected':''?>>Volunteer</option>
          </select>
        </div>
        <div class="form-group col-lg-3">
          <label for="user_id">Select User</label>
          <select name="user_id" id="user_id" class="form-control select2" required>
            <option value="">--Select User--</option>
          </select>
        </div>
        <div class="form-group col-lg-2">
          <label for="from_date">From Date</label>
          <input type="date" name="from_date" id="from_date" class="form-control" value="<?=$from_date?>">
        </div>
        <div class="form-group col-lg-2">
          <label for="to_date">To Date</label>
          <input type="date" name="to_date" id="to_date" class="form-control" value="<?=$to_date?>">
        </div>
        <div class="form-group col-lg-2  mt-4 pt-2">
          <button type="submit" class="btn btn-primary" onclick="return validatefilter()">Submit</button>
          <a href="<?=base_url(ADMINPATH.'attendance-list')?>" class="btn btn-success">Reset</a>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <input type="hidden" value="0" id="totalRecords" />
          <table id="responseData" class="table table-bordered mb-0">
            <div class="d-flex justify-content-end mb-2 gap-2">
              <form action="<?=base_url(ADMINPATH.'exportFullAttendance')?>" method="get">
                <div class="row mx-2">
                  <div class="col-lg-12">
                    <input type="month" name="month" id="month" max="<?= date('Y-m') ?>" class="form-control">
                  </div>
                </div>
                <button class="btn btn-success btn-sm position-relative" type="submit" onclick="return validateExport()"><i class="fa fa-download"></i></button>
              </form>
            </div>
            </form>
          </table>
        </div>
      </div>
      <br>
    </div>
  </div>
</div>
<script>
    function getTotalRecordsData(qparam) {
        $.ajax({
            url: '<?= base_url(ADMINPATH . 'attendance-data'); ?>?' + qparam,
            type: "POST",
            data: { 'is_count': 'yes', 'start': 0, 'length': 10 },
            cache: false,
            success: function (response) {
                $('#totalRecords').val(response);
                    loadAllRecordsData(qparam);
            }
        });
    }

    $(document).ready(function () {
        let qparam = (new URL(location)).searchParams;
        getTotalRecordsData(qparam);
    });

    function loadAllRecordsData(qparam) {
  $('#responseData').html('');
  var newQueryParam = '?' + qparam + '&recordstotal=' + $('#totalRecords').val();
  $('#responseData').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": '<?= base_url(ADMINPATH . 'attendance-data'); ?>'+newQueryParam,
      "type": 'POST',
      dataSrc: (res) => {
        return res.data
      }
    },
    "columns": [{data: "sr_no","name": "Sr.No","title": "Sr.No"},
      {data: "id","name": "User Detail","title": "User Detail","render": user_detail},
      {data: "id","title": "Session Detail","render": session_detail},
      {data: "id","title": "Location Detail","render": login_logout_address},
    //  {data: "query","name": "Query","title": "Query"},
      <?php /*{data: "id","name": "Action","title": "Action","render": action_render} */?>
    ],
    "rowReorder": {
      selector: 'td:nth-child(2)'
    },
    "responsive": false,
    "autoWidth": false,
    "destroy": true,
    "searchDelay": 2000,
    "searching": true,
    "pagingType": 'simple_numbers',
    "rowId": (a) => {
      return 'id_' + a.id;
    },
    "iDisplayLength": 10,
    "order": [3, "asc"],
  });
}

var user_detail = (data, type, row, meta) => {
  var data = '';
  let employee_volunteer_name = row.employee_volunteer_name != null ? row.employee_volunteer_name : "";
  let user_type = row.user_type != null ? row.user_type : "";
  let mobile_no = row.mobile_no != null ? row.mobile_no : "";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Name : </b>' + employee_volunteer_name + '</span><br>';
    data += '<span class="fotr_10"><b>User Type : </b>' + user_type + '</span>';
   
  }
  return data;
}
var session_detail = (data, type, row, meta) => {
  var data = '';
  let login_date = row.login_date != null ? row.login_date : "N/A";
  let login_time = row.login_time != null ? row.login_time : "N/A";
  let logout_date = row.logout_date != null ? row.logout_date : "N/A";
  let logout_time = row.logout_time != null ? row.logout_time : "N/A";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Login Date : </b>' + login_date + '</span><br>';
    data += '<span class="fotr_10"><b>Login Time : </b>' + login_time + '</span><br>';
    data += '<span class="fotr_10"><b>Logout Date : </b>' + logout_date + '</span><br>';
    data += '<span class="fotr_10"><b>Logout Time : </b>' + logout_time + '</span>';

  }
  return data;
}

var login_logout_address = (data, type, row, meta) => {
  var data = '';
  let login_address = row.login_address != '' ? row.login_address : "N/A";
  let logout_address = row.logout_address != '' ? row.logout_address : "N/A";
  let login_coords = row.login_coords != '' ? row.login_coords : "N/A";
  let logout_coords = row.logout_coords != '' ? row.logout_coords : "N/A";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Login At : </b>' + login_address + '</span><br>';
    data += '<span class="fotr_10"><b>Login Coordinates : </b>' + login_coords + '</span><br>';
    data += '<span class="fotr_10"><b>Logout At : </b>' + logout_address + '</span><br>';
    data += '<span class="fotr_10"><b>Logout Coordinates : </b>' + logout_coords + '</span>';
  }
  return data;
}
<?php
if (!empty($user_type)) {
    echo "getUsers('" . $user_type . "', '" . $user_id . "');";
}
?>

function getUsers(val, user_id) {
    $('#user_id').html('');
    $.ajax({
        url: '<?= base_url(ADMINPATH.'getUsers') ?>',
        method: 'POST',
        data: { val: val, user_id: user_id },
        success: function (response) {
            $('#user_id').html(response);
        }
    });
}

function validatefilter() {
    var user_type = $("#user_type").val();
    var user_id = $("#user_id").val();
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
   

    if (user_type === "" && user_id === "" && from_date === "" && to_date === "") {
        toastr.error("Select at least one thing to filter");
        return false;
    } else if(from_date !== "" && to_date === ""){
        toastr.error("Please Select To Date");
        return false;
    } else if(from_date === "" && to_date !== ""){
        toastr.error("Please Select From Date");
        return false;
    }
     else {
        var url = "<?=base_url(ADMINPATH.'attendance-list')?>";
        if (user_type !== "") {
            url += "?user_type=" + user_type;
        }
        if (user_id !== "") {
            url += (url.includes("?") ? "&" : "?") + "user_id=" + user_id;
        }
        if (from_date !== "") {
            url += (url.includes("?") ? "&" : "?") + "from_date=" + from_date;
        }
        if (to_date !== "") {
            url += (url.includes("?") ? "&" : "?") + "to_date=" + to_date;
        }
        
        window.location.href = url;
    }
}

function validateExport(){
    var month=$('#month').val();
    if(month==""){
        toastr.error('Please Select Month');
        return false;
    }
}
</script>