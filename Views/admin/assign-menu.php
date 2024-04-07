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
        <div class="form-group">
         <div class="col-sm-3">
         <label>Choose Designation</label>
         <select onchange="viewAssigned(this.value)" class="form-control select2" name="department" id="department">
            <option value="0">Choose an option</option>
            <?php if(!empty($department_data)){
               foreach ($department_data as $key => $value) { ?>
            <option <?= ($value['id'] == $id) ? 'selected' : '';?> value="<?=($value['id'])?>"><?= $value['department_name'];?></option>
            <?php } }?>
         </select>
      
         </div>
      </div>
        </div>
        <?php 
          $read_assigned = (!empty($department['read_menu_ids'])) ? explode(',',$department['read_menu_ids']) : [];
          $write_assigned = (!empty($department['write_menu_ids'])) ? explode(',',$department['write_menu_ids']) : [];
          ?>
        <div class="card-body">
          <table id="responseData" class="table table-bordered mb-0">
            <thead>
              <tr>
                <th>Sr. No.</th>
                <th>Menu Title</th>
                <th>Menu Type</th>
                <th>Slug</th>
                <th>Priority</th>
                <th>Added On</th>
                <th>Read</th>
                <th>Write</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $i = 1;
                foreach ($menu_data as $key => $value) {
               ?>
              <tr>
                <td><?= $i;?></td>
                <td><?= $value['menu_title'];?></td>
                <td><?= $value['menu_type'];?></td>
                <td><?= $value['slug'];?></td>
                <td><?= $value['priority'];?></td>
                <td><?= $value['add_date'];?></td>
                <td><input <?= (in_array($value['id'], $read_assigned)) ? 'checked' : '' ?> type="checkbox" value="<?= $value['id'];?>" onclick="assignMenu('read_menu')" class="read inline-checkbox"></td>
                <td><input <?= (in_array($value['id'], $write_assigned)) ? 'checked' : '' ?> type="checkbox" value="<?= $value['id'];?>" onclick="assignMenu('write_menu')" class="write inline-checkbox"></td>
              </tr>
              <?php
                $i++;  }
               ?>
            </tbody>
          </table>
        </div>
      </div><br>
    </div>
  </div>
</div>
<script>   
function viewAssigned(id) {
  if (id == "0") {
    window.location.href = "<?= base_url(ADMINPATH.'assign-menu');?>";
  } else {
    window.location.href = "<?= base_url(ADMINPATH.'assign-menu?id=');?>" + id + "";
  }
}

function assignMenu(permission) {
  var read = [];
  $('.read:checked').each(function (i) {
    read[i] = $(this).val();
  });
  var write = [];
  $('.write:checked').each(function (i) {
    write[i] = $(this).val();
  });
  var department = $("#department").val();
  $.ajax({
    url: '<?=base_url(ADMINPATH.('assign_menus?'));?>',
    type : "POST",
    data: {
      'department': department,
      'write': write,
      'read': read,
     // 'column': permission
    },
    cache: false,
    dataType: 'json',
    success: function (response) {
      if (response.status == true) {
        setTimeout(function () {
          toastr.success(response.message)
        }, 1000);
      } else {
        setTimeout(function () {
          toastr.error(response.message)
        }, 1000);
      }
    }
  });

}
</script>