<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a href="<?= base_url(HOSPITALPATH . 'dashboard') ?>">Home</a></li>
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
      <div class="card">
        <div class="card-header pb-2">
          <div class="row justify-content-center">
            <div class="form-group col-lg-2">
              <label for="from_date">From Date</label>
              <?php ?>
              <input type="date" name="from_date" required onchange="validate_to()" id="from_date" class="form-control" value="<?=$from_date?>">
            </div>
            <div class="form-group col-lg-2">
              <label for="to_date">To Date</label>
              <input type="date" name="to_date" required onchange="validate_from()" id="to_date" class="form-control" value="<?=$to_date?>">
            </div>
            <div class="form-group col-lg-2 mt-4 pt-2">
              <button type="submit" class="btn btn-primary" onclick="return validatefilter()">Submit</button>
              <a href="<?=base_url(HOSPITALPATH.'case-history')?>" class="btn btn-success">Reset</a>
            </div>
           
          </div>
        </div>
        <div class="card-body">
          <input type="hidden" value="0" id="totalRecords" />
          <table id="responseData" class="table table-bordered mb-0">
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function getTotalRecordsData(qparam) {
  $.ajax({
    url: '<?= base_url(HOSPITALPATH .'case-history-data');?>?'+qparam,
    type : "POST",
    data: {
      'is_count': 'yes',
      'start': 0,
      'length': 10
    },
    cache: false,
    success: function (response) {
      $('#totalRecords').val(response);
      //if (response) {
      loadAllRecordsData(qparam);
      //}
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
      "url": '<?= base_url(HOSPITALPATH .'case-history-data'); ?>'+newQueryParam,
      "type": 'POST',
      dataSrc: (res) => {
        return res.data
      }
    },
    "columns": [{data: "sr_no","name": "Sr.No","title": "Sr.No"},
      {data: "","name": "Patient Detail","title": "Patient Detail","render": patient_detail},
      {data: "","name": "Medical History","title": "Medical History","render": medical_detail},
      {data: "","name": "Location","title": "Location","render": location_detail},
      {data: "","name": "Dates","title": "Dates","render": dates},
     // {data: "","name": "Status","title": "Status","render": status_render},
      //{data: "id","name": "Action","title": "Action","render": action_render}
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

var patient_detail = (data, type, row, meta) => {
  var data = '';
  let patient_full_name = row.patient_full_name != null ? row.patient_full_name : "";
  let patient_mobile_no = row.patient_mobile_no != null ? row.patient_mobile_no : "";
  let gender = row.gender != null ? row.gender : "";
  let dob = row.dob != null ? row.dob : "";
  let occupation = row.occupation != null ? row.occupation : "";
  let aadhaar_no = row.aadhaar_no != null ? row.aadhaar_no : "";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Name : </b>' + patient_full_name + '</span><br>';
    data += '<span class="fotr_10"><b>Mobile : </b>' + patient_mobile_no + '</span><br>';
    data += '<span class="fotr_10"><b>Gender : </b>' + gender + '</span><br>';
    data += '<span class="fotr_10"><b>DOB : </b>' + dob + '</span><br>';
    data += '<span class="fotr_10"><b>Occupation : </b>' + occupation + '</span><br>';
    data += '<span class="fotr_10"><b>Aadhaar Number : </b>' + aadhaar_no + '</span>';
  }
  return data;
}

var location_detail = (data, type, row, meta) => {
  var data = '';
  let block_name = row.block_name != null ? row.block_name : "";
  let district_name = row.district_name != null ? row.district_name : "";
  let state_name = row.state_name != null ? row.state_name : "";
  let pincode = row.pincode != null ? row.pincode : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Block : </b>' + block_name + '</span><br>';
    data += '<span class="fotr_10"><b>District : </b>' + district_name + '</span><br>';
    data += '<span class="fotr_10"><b>State : </b>' + state_name + '</span><br>';
    data += '<span class="fotr_10"><b>Pincode : </b>' + pincode + '</span>';

  }
  return data;
}

var medical_detail = (data, type, row, meta) => {
  var data = '';
  let hospital_name = row.hospital_name != null ? row.hospital_name : "";
  let disease_name = row.disease_name != null ? row.disease_name : "";
  let employee_name = row.employee_name != null ? row.employee_name : "";
  let volunteer_name = row.volunteer_name != null ? row.volunteer_name : "";
  let attended_by_doctor_name = row.attended_by_doctor_name != null ? row.attended_by_doctor_name : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Hospital : </b>' + hospital_name + '</span><br>';
    data += '<span class="fotr_10"><b>Attended By : </b>' + attended_by_doctor_name + '</span><br>';
    data += '<span class="fotr_10"><b>Disease : </b>' + disease_name + '</span><br>';
    data += '<span class="fotr_10"><b>Employee : </b>' + employee_name + '</span><br>';
    data += '<span class="fotr_10"><b>Volunteer : </b>' + volunteer_name + '</span>';

  }
  return data;
}

var dates = (data, type, row, meta) => {
  var data = '';
  let add_date = row.add_date != null ? row.add_date : "";
  let admit_date = row.admit_date != null ? row.admit_date : "";
  let status = row.status == "add" ? "Added" : "Converted";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Added On : </b>' + add_date + '</span><br>';
    data += '<span class="fotr_10"><b>Admit Date : </b>' + admit_date + '</span>';
    //data += '<span class="fotr_10"><b>Status : </b>' + status + '</span>';
  }
  return data;
}

 
function validatefilter(){
    var from_date=$("#from_date").val();
    var to_date=$("#to_date").val();
   if(from_date==""){
    toastr.error("Please Select From Date");
   }else if(to_date==""){
    toastr.error("Please Select To Date");
   }else{
    window.location.href="<?=base_url(HOSPITALPATH.'case-history?from_date=')?>"+from_date+"&to_date="+to_date+"";
   }
  } 

</script>