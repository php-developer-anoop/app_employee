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
                <div class="card-header">
                    <a href="<?= base_url(ADMINPATH . 'add-menu') ?>" class="btn btn-success m-auto float-right">Add Menu</a>
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
            url: '<?= base_url(ADMINPATH . 'menu-data'); ?>?' + qparam,
            type: "POST",
            data: { 'is_count': 'yes', 'start': 0, 'length': 10 },
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
        var newQueryParam = '?'+qparam + '&recordstotal=' + $('#totalRecords').val();
        $('#responseData').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": '<?= base_url(ADMINPATH . 'menu-data'); ?>' + newQueryParam,
                "type": 'POST',
                dataSrc: (res) => {
                    return res.data
                }
            },
            "columns": [{ data: "sr_no", "name": "Sr.No", "title": "Sr.No" },
            { data: "", "name": "Menu Detail", "title": "Menu Detail","render":menu_detail },
            { data: "", "name": "Dates", "title": "Dates","render":dates },
            { data: "", "name": "Status", "title": "Status", "render": status_render },
            { data: "id", "name": "Action", "title": "Action", "render": action_render }],

            "rowReorder": { selector: 'td:nth-child(2)' },
            "responsive": false,
            "autoWidth": false,
            "destroy": true,
            "searchDelay": 2000,
            "searching": true,
            "pagingType": 'simple_numbers',
            "rowId": (a) => { return 'id_' + a.id; },
            "iDisplayLength": 10,
            "order": [3, "asc"],
        });
    }

    var dates = (data, type, row, meta) => {
  var data = '';
  let add_date = row.add_date != null ? row.add_date : "";
  let priority = row.priority != null ? row.priority : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Added On : </b>' + add_date + '</span><br>';
    data += '<span class="fotr_10"><b>Priority: </b>' + priority + '</span>';

  }
  return data;
}
var menu_detail = (data, type, row, meta) => {
  var data = '';
  let menu_title = row.menu_title != null ? row.menu_title : "";
  let slug = row.slug != null ? row.slug : "";
  let menu_type = row.menu_type != null ? row.menu_type : "";
  if (type === 'display') {
    data += '<span class="fotr_10"><b>Menu Title : </b>' + menu_title + '</span><br>';
    data += '<span class="fotr_10"><b>Slug : </b>' + slug + '</span><br>';
    data += '<span class="fotr_10"><b>Menu Type : </b>' + menu_type + '</span>';
  }
  return data;
}


function action_render(data, type, row, meta) {
  let output = '';
  if (type === 'display') {
    var onclick = "remove('" + row.id + "','dt_menus')";
    output = '<a href="<?= base_url(ADMINPATH . "add-menu?id=") ?>' + row.id + '" class="btn btn-success btn-sm text-white" title="Edit Menu"><i class="fa fa-edit"></i></a> ';
   // output += '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="fa fa-trash"></i></a> ';
  }
  return output;
}

function status_render(data, type, row, meta) {
  if (type === 'display') {
    const isChecked = row.status === 'Active';
    const label = isChecked ? 'Active' : 'Inactive';
    const id = `tableswitch5${row.id}`;
    const onchange = `change_status(${row.id}, 'dt_menus')`;

    return `<div class="custom-control custom-switch">
                <input type="checkbox" onchange="${onchange}" ${isChecked ? 'checked' : ''} class="custom-control-input" id="${id}">
                <label class="custom-control-label" for="${id}" id="label_id">${label}</label>
            </div> `;
  }
  return '';
}

</script>