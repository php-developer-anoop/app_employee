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
          <label for="state">Select State</label>
          <select name="state" id="state" class="form-control select2" required onchange="getDistrictForHospital(this.value,'<?=$district_id?>')">
            <option value="">--Select State--</option>
            <?php if(!empty($state_list)){foreach($state_list as $key=>$value){?>
            <option value="<?=$value['id']?>" <?=!empty($value['id'] && $state_id==$value['id'])?"selected":""?>><?=$value['state_name']?></option>
            <?php }} ?>
          </select>
        </div>
        <div class="form-group col-lg-2">
          <label for="district">Select District</label>
          <select name="district" id="district" class="form-control select2" required onchange="getBlockForHospital(this.value,'<?=$block_id?>')">
            <option value="">--Select District--</option>
          </select>
        </div>
        <div class="form-group col-lg-2">
          <label for="block">Select Block</label>
          <select name="block" id="block" class="form-control select2" >
            <option value="">--Select Block--</option>
          </select>
        </div>
        <div class="form-group col-lg-2">
          <label for="employee">Select Manager</label>
          <select name="employee" id="employee" class="form-control select2">
            <option value="">--Select Manager--</option>
            <?php if(!empty($employee_list)){foreach($employee_list as $ekey=>$evalue){?>
            <option value="<?=$evalue['id']?>" <?=!empty($employee) && ($employee==$evalue['id'])?'selected':''?>><?=$evalue['full_name']?></option>
            <?php }} ?>
          </select>
        </div>
        <div class="form-group col-lg-2">
          <label for="category">Select Category</label>
          <select name="category" id="category" class="form-control select2">
            <option value="">--Select Category--</option>
            <?php if(!empty($category_list)){foreach($category_list as $ckey=>$cvalue){?>
            <option value="<?=$cvalue['id']?>" <?=!empty($category) && ($category==$cvalue['id'])?'selected':''?>><?=$cvalue['category_name']?></option>
            <?php }} ?>
          </select>
        </div>
        <div class="form-group col-lg-2">
          <label for="to_date">Select Scope Of Services</label>
          <select name="disease" id="disease" class="form-control select2">
            <option value="">--Select Service--</option>
            <?php if(!empty($disease_list)){foreach($disease_list as $dkey=>$dvalue){?>
            <option value="<?=$dvalue['id']?>" <?=!empty($speciality) && ($speciality==$dvalue['id'])?'selected':''?>><?=$dvalue['service_name']?></option>
            <?php }} ?>
          </select>
        </div>
        <div class="form-group col-lg-2  pt-0">
          <button type="submit" class="btn btn-primary" onclick="return validatefilter()">Submit</button>
          <a href="<?=base_url(ADMINPATH.'hospital-list')?>" class="btn btn-success">Reset</a>
        </div>
      </div>
      <div class="card ">
        <div class="card-header">
          <a href="<?= base_url(ADMINPATH . 'add-hospital') ?>" class="btn btn-success m-auto float-right">Add Hospital</a>
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
<script>
  function getTotalRecordsData(qparam) {
  $.ajax({
    url: '<?= base_url(ADMINPATH .'hospital-data');?>?'+qparam,
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
      "url": '<?= base_url(ADMINPATH .'hospital-data'); ?>'+newQueryParam,
      "type": 'POST',
      dataSrc: (res) => {
        return res.data
      }
    },
    "columns": [{data: "sr_no","name": "Sr.No","title": "Sr.No"},
      {data: "","name": "Hospital Detail","title": "Hospital Detail","render": hospital_detail},
      {data: "","name": "Location","title": "Location","render": location_detail},
      {data: "","name": "Dates","title": "Dates","render": dates},
      //{data: "","name": "Status","title": "Status","render": status_render},
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

var hospital_detail = (data, type, row, meta) => {
  var data = '';
  let hospital_name = row.hospital_name != null ? row.hospital_name : "";
  let mobile_no = row.mobile_no != null ? row.mobile_no : "";
  let email_id = row.email_id != null ? row.email_id : "";
  let category_name = row.category_name != null ? row.category_name : "";
  let employee_name = row.employee_name != null ? row.employee_name : "";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Hospital : </b>' + hospital_name + '</span><br>';
    data += '<span class="fotr_10"><b>Mobile : </b>' + mobile_no + '</span><br>';
    data += '<span class="fotr_10"><b>Email : </b>' + email_id + '</span><br>';
    data += '<span class="fotr_10"><b>Category : </b>' + category_name + '</span><br>';
    data += '<span class="fotr_10"><b>Reporting Manager : </b>' + employee_name + '</span><br>';

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

var dates = (data, type, row, meta) => {
  var data = '';
  let add_date = row.add_date != null ? row.add_date : "";
  let update_date = row.update_date != null ? row.update_date : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Added On : </b>' + add_date + '</span><br>';
    data += '<span class="fotr_10"><b>Updated On : </b>' + update_date + '</span>';
  }
  return data;
}

function action_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
    var onclick = "remove('" + row.id + "','dt_hospital_list')";
    output = '<a href="<?= base_url(ADMINPATH . "add-hospital?id=") ?>' + row.id + '" class="btn btn-success btn-sm text-white" title="Edit Hospital"><i class="fa fa-edit"></i></a> ';
   // output += '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="fa fa-trash"></i></a> ';
  }
  return output;
}

function status_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
    const isChecked = row.profile_status === 'Active';
    const checkboxId = `tableswitch5${row.id}`;
    const label = isChecked ? 'Active' : 'Inactive';
    const onchange = `change_profile_status(${row.id}, 'dt_hospital_list')`;

    output += `<div class="custom-control custom-switch">
        <input type="checkbox" onchange="${onchange}" class="custom-control-input" id="${checkboxId}" ${isChecked ? 'checked' : ''}/>
        <label class="custom-control-label" for="${checkboxId}">${label}</label>
      </div>`;
  }
  return output;
}

function validatefilter() {
    var category = $("#category").val();
    var employee = $("#employee").val();
    var state = $("#state").val();
    var district = $("#district").val();
    var block = $("#block").val();
    var disease = $("#disease").val();

    if (category === "" && disease === "" && employee === "" && state === "" && district === "" && block === "") {
        toastr.error("Select at least one thing to filter");
        return false;
    } else {
        var url = "<?=base_url(ADMINPATH.'hospital-list')?>";
        if (category !== "") {
            url += "?category=" + category;
        }
        if (employee !== "") {
            url += (url.includes("?") ? "&" : "?") + "employee=" + employee;
        }
        if (state !== "") {
            url += (url.includes("?") ? "&" : "?") + "state=" + state;
        }
        if (district !== "") {
            url += (url.includes("?") ? "&" : "?") + "district=" + district;
        }
        if (block !== "") {
            url += (url.includes("?") ? "&" : "?") + "block=" + block;
        }
        if (disease !== "") {
            url += (url.includes("?") ? "&" : "?") + "speciality=" + disease;
        }
        window.location.href = url;
    }
}


<?php 
if(!empty($district_id)){
?>
  getDistrictForHospital(<?= !empty($state_id) ? $state_id : "" ?>,'<?=$district_id?>');
<?php 
}
?>

function getDistrictForHospital(id,distvalue){
  $('#district').html('');
  $.ajax({
    url: '<?= base_url(ADMINPATH.'getDistrictsForHospital') ?>',
    method: 'POST',
    data: { state_id: id ,district:distvalue},
    success: function (response) {
        $('#district').html(response);
    }
  });
}

<?php 
if(!empty($block_id)){
?>
  getBlockForHospital(<?= !empty($district_id) ? $district_id : "" ?>,'<?=$block_id?>');
<?php 
}
?>

function getBlockForHospital(id,blockvalue){
  $('#block').html('');
  $.ajax({
    url: '<?= base_url(ADMINPATH.'getBlockForHospital') ?>',
    method: 'POST',
    data: { district_id: id,block:blockvalue},
    success: function (response) {
        $('#block').html(response);
    }
  });
}
</script>