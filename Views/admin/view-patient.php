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
          <a href="<?=base_url(ADMINPATH.'patient-list')?>" class="btn btn-success">Reset</a>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <a href="<?= base_url(ADMINPATH . 'add-patient') ?>" class="btn btn-success float-right">Add Patient</a>
        </div>
        <div class="card-body">
          <input type="hidden" value="0" id="totalRecords" />
          <table id="responseData" class="table table-bordered mb-0">
          </table>
        </div>
      </div>
      <br>
    </div>
  </div>
</div>
<div class="modal fade" id="approve" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Convert Patient</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?=form_open(ADMINPATH . 'convert-patient','id="myForm"'); ?>
      <div class="modal-body">
        <div class="form-group row">
          <input type="hidden" name="id" class="rowid" value="">
          <div class="col-sm-12">
            <?=form_label('Select Hospital','hospital',['class'=>'col-form-label'])?>
            <input type="text" name="hospital_name" autocomplete="off" id="hosp_name" placeholder="Type a keyword" class="form-control" onkeyup="getHospitalName(this.value)">
           <input type="hidden" name="hospital_id" id="hosp_id" value="">
            <ul class="autocomplete-list" id="suggestion-list" onclick="return selectHospitalName()"></ul>
          </div>
          <div class="col-sm-6">
            <?=form_label('Admit Date','converted_date',['class'=>'col-form-label'])?>
            <?=form_input(['name' => 'converted_date', 'required' => 'required','autocomplete'=>'off', 'placeholder' => 'YYYY-MM-DD', 'id' => 'datepicker', 'class' => 'form-control']); ?>
          </div>
          <div class="col-sm-6">
            <?=form_label('Admit Time','converted_time',['class'=>'col-form-label'])?>
            <?=form_input(['type'=>'time','name' => 'converted_time', 'required' => 'required','autocomplete'=>'off','id' => 'converted_time', 'class' => 'form-control']); ?>
          </div>
          <div class="col-sm-12">
            <?=form_label('Attended By (Doctor Name)','doctor_name',['class'=>'col-form-label'])?>
            <?=form_input(['name' => 'doctor_name', 'required' => 'required','autocomplete'=>'off', 'placeholder' => 'Enter Doctor Name', 'id' => 'doctor_name', 'class' => 'form-control ucwords restrictedInput']); ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
      <?=form_close();?>
    </div>
  </div>
</div>
<script>
  function getTotalRecordsData(qparam) {
  $.ajax({
    url: '<?= base_url(ADMINPATH .'patient-data');?>?'+qparam,
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
      "url": '<?= base_url(ADMINPATH .'patient-data'); ?>'+newQueryParam,
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
      {data: "id","name": "Action","title": "Action","render": action_render}
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
  let full_name = row.full_name != null ? row.full_name : "";
  let mobile_no = row.mobile_no != null ? row.mobile_no : "";
  let gender = row.gender != null ? row.gender : "";
  let dob = row.dob != null ? row.dob : "";
  let occupation = row.occupation != null ? row.occupation : "";
  let aadhaar_no = row.aadhaar_no != null ? row.aadhaar_no : "";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Name : </b>' + full_name + '</span><br>';
    data += '<span class="fotr_10"><b>Mobile : </b>' + mobile_no + '</span><br>';
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
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Hospital : </b>' + hospital_name + '</span><br>';
    data += '<span class="fotr_10"><b>Speciality : </b>' + disease_name + '</span><br>';
    data += '<span class="fotr_10"><b>Employee : </b>' + employee_name + '</span><br>';
    data += '<span class="fotr_10"><b>Volunteer : </b>' + volunteer_name + '</span>';

  }
  return data;
}

var dates = (data, type, row, meta) => {
  var data = '';
  let add_date = row.add_date != null ? row.add_date : "";
  let update_date = row.update_date != null ? row.update_date : "";
  let status = row.status == "add" ? "Added" : "Converted";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Added On : </b>' + add_date + '</span><br>';
    data += '<span class="fotr_10"><b>Updated On : </b>' + update_date + '</span><br>';
    data += '<span class="fotr_10"><b>Status : </b>' + status + '</span>';
  }
  return data;
}

function action_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
    var appendid = "appendid('" + row.id + "')"
    var onclick = "remove('" + row.id + "','dt_patient_list')";
    output = '<a href="<?= base_url(ADMINPATH . "add-patient?id=") ?>' + row.id + '" class="btn btn-success btn-sm text-white" title="Edit Patient"><i class="fa fa-edit"></i></a> ';
   // output += '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="fa fa-trash"></i></a> ';
   if(row.status=="add"){
    output += '<a href="javascript:void(0)" data-toggle="modal" onclick="' + appendid + '"  data-target="#approve"  class="btn btn-primary btn-sm text-white" title="Convert Patient"><i class="fa fa-exchange" ></i></a> ';
  }
  }
  return output;
}

function appendid(val) {
  $('.rowid').val(val);
}

function validatefilter(){
    var from_date=$("#from_date").val();
    var to_date=$("#to_date").val();
   if(from_date==""){
    setTimeout(function () {
		toastr.error("Please Select From Date")
	}, 200);
   }else if(to_date==""){
       setTimeout(function () {
		toastr.error("Please Select To Date")
	}, 200);
   }else{
    window.location.href="<?=base_url(ADMINPATH.'patient-list?from_date=')?>"+from_date+"&to_date="+to_date+"";
   }
  }
  
  
function getHospitalName(val) {
  $('.autocomplete-list').show();
  $('.autocomplete-list').html('');
  $('#hosp_id').val('');

  if (val !== "") {
    $.ajax({
      url: '<?= base_url(ADMINPATH.'getHospitalName') ?>',
      method: 'POST',
      data: { val: val },
      dataType: "html",
      success: function (response) {
        if (response.length < 20) {
          toastr.error(response);
          return false;
        } else if (response.length > 20) {
          $('.autocomplete-list').html(response);
        }
      }
    });
  }
}

function selectHospitalName() {
  var selectedItem = $(event.target);
  var itemValue = selectedItem.text();
  var itemId = selectedItem.val();

  if (itemValue !== "" && itemId !== "") {
    $('#hosp_name').val(itemValue);
    $('#hosp_id').val(itemId);
    $('.autocomplete-list').hide();
  }
}

$("#suggestion-list").on("click", "li", selectHospitalName);

// $(document).ready(function () {
  // Attach a submit event handler to the form
  $('#myForm').submit(function (event) {
     var hosp_id=$('#hosp_id').val();
     if(hosp_id==""){
         toastr.error('Select Hospital From Suggestions');
         return false;
     }
});
// });

</script>