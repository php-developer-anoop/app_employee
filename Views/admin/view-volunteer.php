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
            <div class="card ">
                <div class="card-header">
                    <a href="<?= base_url(ADMINPATH . 'add-volunteer') ?>" class="btn btn-success m-auto float-right">Add Volunteer</a>
                </div>
                <div class="card-body">
                    <input type="hidden" value="0" id="totalRecords" />
                    <table id="responseData" class="table table-bordered mb-0">
                    </table>
                </div>
            </div><br>
        </div>
    </div>
</div>

<script>
  function getTotalRecordsData(qparam) {
  $.ajax({
    url: '<?= base_url(ADMINPATH .'volunteer-data');?>?'+qparam,
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
      "url": '<?= base_url(ADMINPATH .'volunteer-data'); ?>'+newQueryParam,
      "type": 'POST',
      dataSrc: (res) => {
        return res.data
      }
    },
    "columns": [{data: "sr_no","name": "Sr.No","title": "Sr.No"},
      {data: "employee_name","name": "Employee Name","title": "Employee Name"},
      {data: "","name": "Volunteer Detail","title": "Volunteer Detail","render": volunteer_detail},
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

var volunteer_detail = (data, type, row, meta) => {
  var data = '';
  let full_name = row.full_name != null ? row.full_name : "";
  let mobile_no = row.mobile_no != null ? row.mobile_no : "";
  let email_id = row.email_id != null ? row.email_id : "";
  let date_of_birth = row.date_of_birth != null ? row.date_of_birth : "";
  let gender = row.gender != null ? row.gender : "";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Name : </b>' + full_name + '</span><br>';
    data += '<span class="fotr_10"><b>Mobile : </b>' + mobile_no + '</span><br>';
    data += '<span class="fotr_10"><b>Email : </b>' + email_id + '</span><br>';
    data += '<span class="fotr_10"><b>DOB : </b>' + date_of_birth + '</span><br>';
    data += '<span class="fotr_10"><b>Gender : </b>' + gender + '</span>';
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
    var onclick = "remove('" + row.id + "','dt_volunteer_list')";
    output = '<a href="<?= base_url(ADMINPATH . "add-volunteer?id=") ?>' + row.id + '" class="btn btn-success btn-sm text-white" title="Edit Volunteer"><i class="fa fa-edit"></i></a> ';
    //output += '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="fa fa-trash"></i></a> ';
  }
  return output;
}

function status_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
    const isChecked = row.profile_status === 'Active';
    const checkboxId = `tableswitch5${row.id}`;
    const label = isChecked ? 'Active' : 'Inactive';
    const onchange = `change_profile_status(${row.id}, 'dt_volunteer_list')`;

    output += `<div class="custom-control custom-switch">
        <input type="checkbox" onchange="${onchange}" class="custom-control-input" id="${checkboxId}" ${isChecked ? 'checked' : ''}/>
        <label class="custom-control-label" for="${checkboxId}">${label}</label>
      </div>`;
  }
  return output;
}

</script>