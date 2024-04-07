<style>
    .detail-info {
  display: inline-block;
  width: 25%; /* Adjust this width according to your requirement */
}
</style>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Attendance Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?=base_url(ADMINPATH.'dashboard')?>">Home</a></li>
            <li class="breadcrumb-item active">Attendance Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-6 block_size">
            <a href="<?=base_url(ADMINPATH.'attendance?type=Total')?>">
          <div class="small-box bg-white border-0 rounded-3 shadow">
            <div class="inner pl-4">
              <i class="fa fa-users" aria-hidden="true"></i>
              <p class="mb-0">Total Workforce</p>
              <h4 id="total" class="font-weight-bold">00</h4>
            </div>
          </div></a>
        </div>
        <div class="col-lg-3 col-6 block_size">
            <a href="<?=base_url(ADMINPATH.'attendance?type=Present')?>">
          <div class="small-box bg-white border-0 rounded-3 shadow">
            <div class="inner pl-4">
              <i class="fa fa-users" aria-hidden="true"></i>
              <p class="mb-0">Present Workforce</p>
              <h4 id="present" class="font-weight-bold">00</h4>
            </div>
          </div></a>
        </div>
        <div class="col-lg-3 col-6 block_size">
            <a href="<?=base_url(ADMINPATH.'attendance?type=Absent')?>">
          <div class="small-box bg-white border-0 rounded-3 shadow">
            <div class="inner pl-4">
              <i class="fa fa-users" aria-hidden="true"></i>
              <p class="mb-0">Absent Workforce</p>
              <h4 id="absent" class="font-weight-bold">00</h4>
            </div>
          </div></a>
        </div>
        <div class="col-lg-3 col-6 block_size">
            <a href="<?=base_url(ADMINPATH.'attendance?type=Late')?>">
          <div class="small-box bg-white border-0 rounded-3 shadow">
            <div class="inner pl-4">
              <i class="fa fa-users" aria-hidden="true"></i>
              <p class="mb-0">Late Arrivals</p>
              <h4 id="late" class="font-weight-bold">00</h4>
            </div>
          </div></a>
        </div>
        
      </div>
       <h5>Last 10 Workforce Login</h5>
      <div class="card">
       
        <div class="card-body">
          <input type="hidden" value="10" id="totalRecords" />
          <table id="responseData" class="table table-bordered mb-0">
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function getTotalWorkForce(){  
    $.ajax({
      url: '<?= base_url(ADMINPATH.'getTotalWorkForce') ?>',
      type: "POST",
      cache: false,
        success:function(response) {  
             if(response){
         $('#total').html(response);
             }
        }
      });
    }
    function getPresentWorkForce(){  
    $.ajax({
      url: '<?= base_url(ADMINPATH.'getPresentWorkForce') ?>',
      type: "POST",
      cache: false,
        success:function(response) {  
             if(response){
         $('#present').html(response);
             }
        }
      });
    }
     function getLateWorkForce(){  
    $.ajax({
      url: '<?= base_url(ADMINPATH.'getLateWorkForce') ?>',
      type: "POST",
      cache: false,
        success:function(response) { 
           if(response){
               $('#late').html(response);
           }
         
        }
      });
    }
     function getAbsentWorkForce(){  
    $.ajax({
      url: '<?= base_url(ADMINPATH.'getAbsentWorkForce') ?>',
      type: "POST",
      cache: false,
        success:function(response) { 
             if(response){
         $('#absent').html(response);
             }
        }
      });
    }
    function loaddatavalue(){  
  
  setTimeout( ()=>{ getTotalWorkForce(  ); },500 );
  setTimeout( ()=>{ getPresentWorkForce(  ); },700 );
  setTimeout( ()=>{ getAbsentWorkForce(  ); },900 );
  setTimeout( ()=>{ getLateWorkForce(  ); },1100 );
 
}
  
  window.load = loaddatavalue();
  
  
    function getTotalRecordsData(qparam) {
        $.ajax({
            url: '<?= base_url(ADMINPATH . 'last-present-records'); ?>?' + qparam,
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
      "url": '<?= base_url(ADMINPATH . 'last-present-records'); ?>'+newQueryParam,
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
    // "rowReorder": {
    //   selector: 'td:nth-child(2)'
    // },
    "responsive": false,
    //"autoWidth": false,
    "destroy": true,
    "searchDelay": 1000,
    "searching": true,
    "pagingType": 'simple_numbers',
    "rowId": (a) => {
      return 'id_' + a.id;
    },
    "iDisplayLength": 10,
    "order": [2, "asc"],
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
    data += '<div style="width:auto !important"> ';
    data += '<span class="fotr_10"><b>Login At : </b>' + login_address + '</span><br>';
    data += '<span class="fotr_10"><b>Login Coordinates : </b>' + login_coords + '</span><br>';
    data += '<span class="fotr_10"><b>Logout At : </b>' + logout_address + '</span><br>';
    data += '<span class="fotr_10"><b>Logout Coordinates : </b>' + logout_coords + '</span>';
    data += '</div>'; 
  }
  return data;
}




  </script>