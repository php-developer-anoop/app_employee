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
  $(function () {
    $('.select2').select2()
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

  })
 $(document).ready(function() {
  const $element = $('#pushmenu');

  if ($element.length > 0) {
    $element.click();
  }
});
function validate_from(){
		var max = $("#to_date").val();
		$("#from_date").attr("max", max);
	
	}
	
	function validate_to(){
		var min = $("#from_date").val();
		$("#to_date").attr("min", min);
		$("#to_date").val(min);
	} 


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
       alert("Duplicate Entry For this Mobile Number");
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
</script>
</body>

</html>