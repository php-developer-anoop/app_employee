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
<!-- Approve Modal -->
<div class="modal fade" id="approve" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Approve Leave</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?=form_open(ADMINPATH . 'approve_reject_leave'); ?>
      <div class="modal-body">
        <?=form_hidden('leave_type','approved')?>
        <div class="form-group row">
          <input type="hidden" name="id" class="rowid" value="">
          <div class="col-sm-12">
            <?=form_label('Reason','reason',['class'=>'col-form-label'])?>
            <?= form_input(['name' => 'reason', 'required' => 'required', 'placeholder' => 'Enter Reason', 'id' => 'reason', 'class' => 'form-control ucwords restrictedInput','value'=>'']); ?>
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
<!-- Reject Modal -->
<div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reject Leave</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?=form_open(ADMINPATH . 'approve_reject_leave'); ?>
      <div class="modal-body">
        <?=form_hidden('leave_type','reject')?>
        <input type="hidden" name="id" class="rowid" value="">
        <div class="form-group row">
          <div class="col-sm-12">
            <?=form_label('Reason','reason',['class'=>'col-form-label'])?>
            <?= form_input(['name' => 'reason', 'required' => 'required', 'placeholder' => 'Enter Reason', 'id' => 'reason', 'class' => 'form-control ucwords restrictedInput','value'=>'']); ?>
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
            url: '<?= base_url(ADMINPATH . 'leaves-data'); ?>?' + qparam,
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
      "url": '<?= base_url(ADMINPATH . 'leaves-data'); ?>'+newQueryParam,
      "type": 'POST',
      dataSrc: (res) => {
        return res.data
      }
    },
    "columns": [{data: "sr_no","name": "Sr.No","title": "Sr.No"},
      {data: "","name": "User Detail","title": "User Detail","render": user_detail},
      {data: "","name": "Leave Detail","title": "Leave Detail","render": leave_detail},
      {data: "","name": "Dates","title": "Dates","render": dates},
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

var user_detail = (data, type, row, meta) => {
  var data = '';
  let employee_volunteer_name = row.employee_volunteer_name != null ? row.employee_volunteer_name : "";
  let user_type = row.user_type != null ? row.user_type : "";

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Name : </b>' + employee_volunteer_name + '</span><br>';
    data += '<span class="fotr_10"><b>User Type : </b>' + user_type + '</span>';

  }
  return data;
}
var leave_detail = (data, type, row, meta) => {
  var data = '';
  let leave_reason = row.leave_reason != null ? row.leave_reason : "";
  let status = row.status != null ? row.status : "";
  let comment = row.status == "approved" ? row.comment : row.reject_reason;

  if (type === 'display') {
    data += '<span class="fotr_10"><b>Leave Reason : </b>' + leave_reason + '</span><br>';
    data += '<span class="fotr_10"><b>Status : </b>' + status + '</span><br>';
    data += '<span class="fotr_10"><b>Comment : </b>' + comment + '</span>';

  }
  return data;
}
var dates = (data, type, row, meta) => {
  var data = '';
  let apply_date = row.apply_date != null ? row.apply_date : "";
  let action_date = row.action_date != null ? row.action_date : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Applied On : </b>' + apply_date + '</span><br>';
    data += '<span class="fotr_10"><b>Action On : </b>' + action_date + '</span>';
  }
  return data;
}

function action_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
    var onclick = "appendid('" + row.id + "')"
    var approve = row.status != "pending" ? "disabled" : "";
    var reject = row.status != "pending" ? "disabled" : "";
    output = '<a href="javascript:void(0)" data-toggle="modal" onclick="' + onclick + '"  data-target="#approve"  class="btn btn-success btn-sm text-white ' + approve + '"  title="Approve Leave"><i class="fa fa-check" ></i></a> ';
    output += '<a class="btn btn-sm btn-danger text-white ' + reject + '" onclick="' + onclick + '"   href="javascript:void(0)" data-toggle="modal"  data-target="#reject"  title="Reject Leave"><i class="fa fa-cancel"></i></a> ';
  }
  return output;
}

function appendid(val) {
  $('.rowid').val(val);
}

</script>