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
      <div class="row mt-2">
        <div class="col-3">
          <div class="col-sm-12">
            <?=form_label('Select Employee <span class="text-danger">*</span>','employee',['class'=>'col-form-label'])?>
            <input type="text" name="employee_name" autocomplete="off" id="hosp_name" placeholder="Type a keyword" class="form-control" onkeyup="getEmployeeName(this.value)">
            <input type="hidden" name="employee_id" id="empl_id" value="">
            <ul class="autocomplete-list" id="suggestion-employee-list" onclick="return selectEmployeeName()"></ul>
          </div>
        </div>
        <div class="col-6">
          <?=form_label('Select Hospital <span class="text-danger">*</span>','hospital',['class'=>'col-form-label'])?>
          <select name="hospital[]" id="hospital" class="form-control select2" required multiple>
            <?php if(!empty($hospital_list)){foreach($hospital_list as $hkey=>$hvalue){?>
            <option value="<?=$hvalue['id']?>" <?=!empty($hospitalIdsArray) && in_array($hvalue['id'],$hospitalIdsArray)?"selected":""?>><?=$hvalue['hospital_name']?></option>
            <?php } } ?>
          </select>
        </div>
        <div class="col-1 text-center">
          <a onclick="return assign_hospital()" class="btn btn-sm btn-success assign">Assign</a> 
        </div>
        <div class="col-1 text-left">
          <a href="<?=base_url(ADMINPATH.'employee-hospitals-list')?>" class="btn btn-sm btn-primary assign">View</a> 
        </div>
      </div>
      <div class="card mt-2">
        <?php if(!empty($assigned_hospitals)){?>
        <div class="card-body">
          <h4>Hospitals Linked To Employee (<?=$full_name?>)  </h4>
          <ul>
            <?php foreach($assigned_hospitals as $ahkey=>$ahvalue){?>
            <li><?=$ahvalue['hospital_name']?></li>
            <?php } ?>
          </ul>
        </div>
        <?php } ?>
      </div>
      <br>
    </div>
  </div>
</div>
<script>
function getEmployeeName(val) {
  $('.autocomplete-list').show();
  $('.autocomplete-list').html('');
  $('#empl_id').val('');

// $('#hospital').val(null).trigger('change');
  if (val !== "") {
    $.ajax({
      url: '<?= base_url(ADMINPATH.'getEmployeeName') ?>',
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

function selectEmployeeName() {
  var selectedItem = $(event.target);
  var itemValue = selectedItem.text();
  var itemId = selectedItem.val();

  if (itemValue !== "" && itemId !== "") {
    $('#hosp_name').val(itemValue);
    $('#empl_id').val(itemId);
    $('.autocomplete-list').hide();
  }
}

$("#suggestion-employee-list").on("click", "li", selectEmployeeName);

function assign_hospital(){
   var empl_id=$('#empl_id').val(); 
   var hospitals=$('#hospital').val();
   if(empl_id.trim()==""){
       toastr.error("Employee Name is Blank");
       return false;
   }else if(hospitals==""){
     toastr.error("Select Hospitals");  
      return false;
   }else{
     $.ajax({
      url: '<?= base_url(ADMINPATH.'assignHospital') ?>',
      method: 'POST',
      data: { employee_id: empl_id,hospitals:hospitals },
      dataType: "json",
      success: function (response) {
        if (response.status==true) {
          toastr.success(response.message);
         window.location.href="<?= base_url(ADMINPATH.'assign-hospital?id=');?>" + response.id + "";
        }else if(response.status==false){
            toastr.success(response.message);
        }
      }
    });  
   }
    
}
function jumptolink(){
    var empl_id=$('#empl_id').val();
    if(empl_id.trim()==""){
       toastr.error("Employee Name is Blank");
       return false;
   }else{
      window.location.href="<?= base_url(ADMINPATH.'assign-hospital?id=');?>" + empl_id + ""; 
   }
}


// Store previously selected values
var prevSelectedValues = [];

$('.select2').on('select2:select', function (e) {
  // Update the previously selected values array when a new option is selected
  var value = e.params.data.id;
  if (!prevSelectedValues.includes(value)) {
    prevSelectedValues.push(value);
  }
});

$('.select2').on('keydown', function (e) {
  if (e.keyCode === 13) { // Check for Enter key (key code 13)
    e.preventDefault();
    
    // Reset Select2 without losing the previous selections
    $(this).val(prevSelectedValues).trigger('change.select2');
  }
});

// Initialize Select2
$('.select2').select2();
</script>