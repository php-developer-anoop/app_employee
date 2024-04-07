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
                    <a href="<?= base_url(ADMINPATH . 'add-notification') ?>" class="btn btn-success m-auto float-right">Add Notification</a>
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
            url: '<?= base_url(ADMINPATH . 'notification-data'); ?>?' + qparam,
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
                "url": '<?= base_url(ADMINPATH . 'notification-data'); ?>' + newQueryParam,
                "type": 'POST',
                dataSrc: (res) => {
                    return res.data
                }
            },
            "columns": [{ data: "sr_no", "name": "Sr.No", "title": "Sr.No" },
            { data: "title", "name": "Title", "title": "Title" ,"render":noti_detail},
           
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

    var noti_detail = ( data, type, row, meta )=>{
  var data = '';
  let title= row.title!=null?row.title:"";
  let app_type= row.app_type!=null?row.app_type:"";
  if(type === 'display'){
        data += '<span class="fotr_10"><b>Title : </b>'+title+'</span><br>' ;
        data += '<span class="fotr_10"><b>App Type : </b>'+app_type+'</span>' ;
      
  }
return data;
}

var dates = ( data, type, row, meta )=>{
  var data = '';
  let add_date= row.add_date!=null?row.add_date:"";
  let upd_date= row.update_date!=null?row.update_date:"";
  if(type === 'display'){
        data += '<span class="fotr_10"><b>Added On : </b>'+add_date+'</span><br>' ;
        data += '<span class="fotr_10"><b>Updated On : </b>'+upd_date+'</span>' ;
      
  }
return data;
}

    function action_render(data, type, row, meta) {
        let output = '';
        if (type === 'display') {
            var broadcast = "sendNotifications('" + row.id + "',0,100)";
            var onclick = "remove('" + row.id + "','dt_notification_master')";
            output = '<a href="<?= base_url(ADMINPATH . "add-notification?id=") ?>' + row.id + '" class="btn btn-success btn-sm text-white" title="Edit Notification"><i class="fa fa-edit"></i></a> ';
          //  output += '<a class="btn btn-sm btn-danger text-white" onclick="' + onclick + '"><i class="fa fa-trash"></i></a> ';
            output += '<a class="btn btn-sm btn-primary text-white"  onclick="' + broadcast + '" title="Send Notification"><i class="fa fa-broadcast-tower"></i></a> ';
            // Assuming you'll do something with the 'output' variable here
        }


        return output;
    }
   function status_render(data, type, row, meta) {
    if (type === 'display') {
        const isChecked = row.status === 'Active';
        const label = isChecked ? 'Active' : 'Inactive';
        const id = `tableswitch5${row.id}`;
        const onchange = `change_status(${row.id}, 'dt_notification_master')`;

        return `<div class="custom-control custom-switch">
                <input type="checkbox" onchange="${onchange}" ${isChecked ? 'checked' : ''} class="custom-control-input" id="${id}">
                <label class="custom-control-label" for="${id}" id="label_id${row.id}">${label}</label>
            </div> `;
    }
    return '';
}
function sendNotifications(id, start, limit) {

    $.ajax({
    url: '<?= base_url(ADMINPATH) . ('push-notification?'); ?>',
    type: "POST",
    data: { 'id': id, 'start': start, 'limit': limit },
    async: true,
    crossDomain: true,
    dataType: "json",  // Add this line to specify JSON data type
    success: function (obj) {  // The response is already parsed as JSON
        if (obj.status) {
            var nid = obj.id;
            var nstart = obj.start;
            var nlimit = obj.limit;
            setTimeout(function () {
                sendNotifications(nid, nstart, nlimit);
            }, 300);
        } else if (!obj.status) {
            window.location.href = '<?=  base_url(ADMINPATH) . 'notification-list'; ?>';
        }
    }
});


}
</script>