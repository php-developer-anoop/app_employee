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
      <div class="card">
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
            url: '<?= base_url(ADMINPATH . 'query-data'); ?>?' + qparam,
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
      "url": '<?= base_url(ADMINPATH . 'query-data'); ?>'+newQueryParam,
      "type": 'POST',
      dataSrc: (res) => {
        return res.data
      }
    },
    "columns": [{data: "sr_no","name": "Sr.No","title": "Sr.No"},
      {data: "","name": "User Detail","title": "User Detail","render": user_detail},
      {data: "","name": "User Type Detail","title": "User Type Detail","render": user_type_detail},
      {data: "query","name": "Query","title": "Query"},
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
  let name = row.name != null ? row.name : "";
  let email_id = row.email_id != null ? row.email_id : "";
  let mobile_no = row.mobile_no != null ? row.mobile_no : "";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Name : </b>' + name + '</span><br>';
    data += '<span class="fotr_10"><b>Email : </b>' + email_id + '</span><br>';
    data += '<span class="fotr_10"><b>Mobile : </b>' + mobile_no + '</span><br>';
  }
  return data;
}
var user_type_detail = (data, type, row, meta) => {
  var data = '';
  let user_type = row.user_type != null ? row.user_type : "";
  let add_date = row.add_date != null ? row.add_date : "";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>User Type : </b>' + user_type + '</span><br>';
    data += '<span class="fotr_10"><b>Added On : </b>' + add_date + '</span>';
  }
  return data;
}

<?php /*
function action_render(data, type, row, meta) {
        let output = '';
        if (type === 'display') {
           
            var onclick = "remove('" + row.id + "','dt_contact_query')";
            output = '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="fa fa-trash"></i></a> ';
            // Assuming you'll do something with the 'output' variable here
        }
        return output;
    }
*/
?>
</script>