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
    url: '<?= base_url(ADMINPATH .'employee-hospital-data');?>?'+qparam,
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
      "url": '<?= base_url(ADMINPATH .'employee-hospital-data'); ?>'+newQueryParam,
      "type": 'POST',
      dataSrc: (res) => {
        return res.data
        // console.log(res.data);
        // return false;
      }
    },
    "columns": [{data: "sr_no","title": "Sr.No"},
      {data: "full_name","title": "Employee Name"},
      {data: "assigned_hospitals","title": "Affiliation Hospitals"},

      {data: "id","title": "Action","render": action_render}
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


function action_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
 //   var onclick = "remove('" + row.id + "','dt_employee_list')";
    output = '<a href="<?= base_url(ADMINPATH . "assign-hospital?id=") ?>' + row.id + '" class="btn btn-success btn-sm text-white" title="Edit Employee"><i class="fa fa-edit"></i></a> ';
   // output += '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="fa fa-trash"></i></a> ';
  }
  return output;
}



</script>