<?=script_tag(base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'))."\n"?>
<?=script_tag(base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'))."\n"?>
<?=script_tag('//cdn.jsdelivr.net/npm/sweetalert2@11')."\n"?>
<?=script_tag(base_url('assets/dist/js/adminlte.min.js'))."\n"?>
<?=script_tag(base_url('assets/plugins/toastr/toastr.min.js'))."\n"?>
<?=script_tag(base_url('assets/plugins/datatables/jquery.dataTables.min.js'))."\n"?>
<?=script_tag(base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'))."\n"?>
<?=script_tag(base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js'))."\n"?>
<?=script_tag(base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js'))."\n"?>
<?=script_tag(base_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js'))."\n"?>
<?=script_tag(base_url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js'))."\n"?>
<?=script_tag('//cdn.ckeditor.com/4.16.2/standard/ckeditor.js')."\n"?>
<?=script_tag('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js')."\n"?>
<?=script_tag('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js')."\n"?>
<?=script_tag(base_url('assets/plugins/select2/js/select2.full.min.js'))."\n"?>
<?=script_tag(base_url('assets/common.js'))."\n"?>

<script>
CKEDITOR.replace('htmeditor');
$(document).ready(function() {
    const $element = $('#pushmenu');

    if ($element.length > 0) {
        $element.click();
    }
});
	$(function () {
		var Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 2000
		});
		<?php if (session()->getFlashdata('success')) { ?>
			setTimeout(function () {
				toastr.success('<?php echo session()->getFlashdata('success'); ?>')
			}, 1000);
		<?php } ?>
		<?php if (session()->getFlashdata('failed')) { ?>
			setTimeout(function () {
				toastr.error('<?php echo session()->getFlashdata('failed'); ?>.')
			}, 1000);
		<?php } ?>
	});
	

  function deleteRecord(id, table) {
    $.ajax({
      url: '<?= base_url(ADMINPATH .'deleteRecords'); ?>',
      type: "POST",
      data: { 'id': id, 'table': table },
      cache: false,
      success: function (response) {
        $(document).ready(function () {
          let qparam = (new URL(location)).searchParams;
          getTotalRecordsData(qparam);
        });
      }
    });
  }
<?php 
if(!empty($district_id) && !empty($district_name)){
  $dist = (!empty($district_id) ? $district_id : '') . ', ' . (!empty($district_name) ? $district_name : '');
?>
  getDistricts(<?= !empty($state_id) ? $state_id : "" ?>,'<?=$dist?>');
<?php 
}
?>

function getDistricts(id,distvalue){
  $('#district').html('');
  $.ajax({
    url: '<?= base_url(ADMINPATH.'getDistrictsFromAjax') ?>',
    method: 'POST',
    data: { state_id: id ,district:distvalue},
    success: function (response) {
        $('#district').html(response);
        gethospital();
    }
  });
}

<?php 
if(!empty($block_id) && !empty($block_name)){
  $blockvalue = (!empty($block_id) ? $block_id : '') . ', ' . (!empty($block_name) ? $block_name : '');
?>
  getBlock(<?= !empty($district_id) ? $district_id : "" ?>,'<?=$blockvalue?>');
<?php 
}
?>

function getBlock(id,blockvalue){
  $('#block').html('');
  $.ajax({
    url: '<?= base_url(ADMINPATH.'getBlockFromAjax') ?>',
    method: 'POST',
    data: { district_id: id,block:blockvalue},
    success: function (response) {
        $('#block').html(response);
    }
  });
}


function gethospital(){
    $('#hospital').html('');
 
  var district_id=$('#district').val();
  var state_id=$('#state').val();
  var employee_id=$('#employee_id').val();
 
  $.ajax({
    url: '<?= base_url(ADMINPATH.'getHospitalFromAjax') ?>',
    method: 'POST',
    data: { district_id:district_id,state_id:state_id,employee_id:employee_id},
    success: function (response) {
        $('#hospital').html(response);
    }
  });
  }
  <?php 
if (!empty($volunteer_id) && !empty($volunteer_name)) {
    $volunteerValue = (!empty($volunteer_id) ? $volunteer_id : '') . ', ' . (!empty($volunteer_name) ? $volunteer_name : '');
    $employeeId = !empty($employee_id) ? $employee_id : "";
?>
    getEmployee(<?= $employeeId ?>, '<?= $volunteerValue ?>');
<?php 
}
?>

function getEmployee(id,volunteervalue){
  $('#volunteer_name').html('');
  $.ajax({
    url: '<?= base_url(ADMINPATH.'getVolunteerFromAjax') ?>',
    method: 'POST',
    data: { employee_id: id,volunteer:volunteervalue},
    success: function (response) {
        $('#volunteer_name').html(response);
       
    }
  });
}

function change_status(id, table) {
    $.ajax({
      url: '<?= base_url(ADMINPATH.'changeStatus') ?>',
      type: "POST",
      data: { 'id': id, 'table': table },
      cache: false,
      success: function (response) {
          $('#label_id'+id).html(response);
      }
    });
  }
  function change_profile_status(id, table) {
    $.ajax({
      url: '<?= base_url(ADMINPATH.'changeProfileStatus') ?>',
      type: "POST",
      data: { 'id': id, 'table': table },
      cache: false,
      success: function (response) {
        $(document).ready(function () {
          let qparam = (new URL(location)).searchParams;
          getTotalRecordsData(qparam);
        });
      }
    });
  }

  function change_patient_status(id, table) {
    $.ajax({
      url: '<?= base_url(ADMINPATH.'changePatientStatus') ?>',
      type: "POST",
      data: { 'id': id, 'table': table },
      cache: false,
      success: function (response) {
        $(document).ready(function () {
          let qparam = (new URL(location)).searchParams;
          getTotalRecordsData(qparam);
        });
      }
    });
  }
 
  function checkDuplicate() {
  var emailId = $('#email_id').val();
  var id = $('#id').val();
  if (emailId !== "" && id == "") {
    $.ajax({
      url: '<?= base_url(ADMINPATH.'checkDuplicateHospital') ?>',
      type: 'POST',
      data: {
        'email_id': emailId
      },
      cache: false,
      success: function (response) {
        if(response && response=="yes"){
       toastr.error("Duplicate Entry For this Email Id");
       $('#submit').addClass('disabled', true);
          return false;
        }
        $('#submit').removeClass('disabled', true);
      }
    });
  }
}  
function checkDuplicateUser(emailId) {

  if (emailId !== "") {
    $.ajax({
      url: '<?= base_url(ADMINPATH.'checkDuplicateUser') ?>',
      type: 'POST',
      data: {
        'email_id': emailId
      },
      cache: false,
      success: function (response) {
        if(response && response=="yes"){
       toastr.error("Duplicate Entry For this Email Id");
       $('#submit').addClass('disabled', true);
          return false;
        }
        $('#submit').removeClass('disabled', true);
      }
    });
  }
} 
function checkDuplicateEmployee(val) {
  var id = $('#employee_id').val();
  if (val !== "" && id == "") {
    $.ajax({
      url: '<?= base_url(ADMINPATH.'checkDuplicateEmployee') ?>',
      type: 'POST',
      data: {
        'email_id': val
      },
      cache: false,
      success: function (response) {
        if(response && response=="yes"){
       toastr.error("Duplicate Entry For this Email Id");
       $('#submit').addClass('disabled', true);
          return false;
        }
        $('#submit').removeClass('disabled', true);
      }
    });
  }
}
function checkDuplicateVolunteer(val) {
  var id = $('#volunteer_id').val();
  if (val !== "" && id == "") {
    $.ajax({
      url: '<?= base_url(ADMINPATH.'checkDuplicateVolunteer') ?>',
      type: 'POST',
      data: {
        'email_id': val
      },
      cache: false,
      success: function (response) {
        if(response && response=="yes"){
       toastr.error("Duplicate Entry For this Email Id");
       $('#submit').addClass('disabled', true);
          return false;
        }
        $('#submit').removeClass('disabled', true);
      }
    });
  }
}
function checkDuplicatePatient(val) {
  var id = $('#patient_id').val();
  if (val !== "" && id == "") {
    $.ajax({
      url: '<?= base_url(ADMINPATH.'checkDuplicatePatient') ?>',
      type: 'POST',
      data: {
        'mobile_no': val
      },
      cache: false,
      success: function (response) {
        if(response && response=="yes"){
       toastr.error("Duplicate Entry For this Mobile Number");
       $('#submit').addClass('disabled', true);
          return false;
        }
        $('#submit').removeClass('disabled', true);
      }
    });
  }
}
$(document).ready(function(){
    $('#timepicker').timepicker();
  });

  function getSlug(val){
        $.ajax({
      url: '<?= base_url(ADMINPATH.'getSlug') ?>',
      type: 'POST',
      data: {
        'keyword': val
      },
      cache: false,
      success: function (response) {
       $('#menu_slug').val(response);
      }
    });
    }

    
</script>
</body>

</html>