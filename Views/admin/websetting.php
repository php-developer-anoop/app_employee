<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a href="<?= base_url(ADMINPATH . 'dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item active">
              <?= $title ?>
            </li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <?=form_open_multipart(ADMINPATH . 'save-setting'); ?>
          <?=form_hidden('id',!empty($web['id'])?$web['id']:"")?>
          <?=form_hidden('old_logo_jpg',!empty($web['logo_jpg'])?$web['logo_jpg']:"")?>
          <?=form_hidden('old_logo_webp',!empty($web['logo_webp'])?$web['logo_webp']:"")?>
          <?=form_hidden('old_favicon',!empty($web['favicon'])?$web['favicon']:"")?>
          <div class="row">
            <div class="form-group col-lg-3">
              <label for="company_name" class=" col-form-label">Company Name</label>
              <?= form_input(['name' => 'company_name', 'required' => 'required', 'placeholder' => 'Enter Company Name', 'id' => 'company_name', 'class' => 'form-control ucwords restrictedInput','value'=>!empty($web['company_name']) ? $web['company_name']:'']); ?>
            </div>
            <div class="form-group col-lg-3">
              <label for="care_mobile_no" class=" col-form-label">Care Mobile (Don't Use +)</label>
              <?= form_input(['name' => 'care_mobile_no', 'required' => 'required', 'placeholder' => 'Enter Care Mobile', 'id' => 'care_mobile_no','maxlength'=>'13', 'class' => 'form-control notzero numbersWithZeroOnlyInput','value'=>!empty($web['care_mobile_no']) ? $web['care_mobile_no']:'']); ?>
            </div>
            <div class="form-group col-lg-3">
              <label for="care_whatsapp_no" class=" col-form-label">Care Whatsapp No.(Don't Use +)</label>
              <?= form_input(['name' => 'care_whatsapp_no', 'required' => 'required', 'placeholder' => 'Enter Care Email', 'id' => 'care_whatsapp_no','maxlength'=>'13', 'class' => 'form-control notzero numbersWithZeroOnlyInput','value'=>!empty($web['care_whatsapp_no']) ? $web['care_whatsapp_no']:'']); ?>
            </div>
            <div class="form-group col-lg-3">
              <label for="care_email_id" class=" col-form-label">Care Email</label>
              <?= form_input(['name' => 'care_email_id', 'required' => 'required', 'placeholder' => 'Enter Care Email', 'id' => 'care_email_id', 'class' => 'form-control emailInput','value'=>!empty($web['care_email_id']) ? $web['care_email_id']:'']); ?>
            </div>
          </div>
          <div class="row">
           
            <div class="col-sm-4">
              <label for="copyright" class="col-form-label">Copyright</label>
              <?= form_input(['name' => 'copyright', 'required' => 'required', 'placeholder' => 'Enter Copyright', 'id' => 'copyright', 'class' => 'form-control','value'=>!empty($web['copyright']) ? $web['copyright']:'']); ?>
            </div>
            <div class="col-sm-8">
              <label for="office_address" class="col-form-label">Full Address</label>
              <?= form_input(['name' => 'office_address', 'required' => 'required', 'placeholder' => 'Enter Full Address', 'id' => 'office_address', 'class' => 'form-control','value'=>!empty($web['office_address']) ? $web['office_address']:'']); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-12">
              <label for="map_script" class="col-form-label">Map Iframe</label>
              <?= form_input(['name' => 'map_script', 'required' => 'required', 'placeholder' => 'Enter Map Iframe', 'id' => 'map_script', 'class' => 'form-control','value'=>!empty($web['map_script']) ? $web['map_script']:'']); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-4">
              <label for="logo" class="col-form-label">Logo</label>
              <?= form_upload(['name'=> 'logo_img','class'=> 'form-control','accept'=>'image/png, image/jpg, image/jpeg' ]); ?>
            </div>
            <div class="col-sm-4">
              <label for="banner_image_alt" class="col-form-label">Logo Alt</label>
              <?= form_input(['name'=> 'logo_alt','required'=>'required','placeholder'=>'Logo Alt','id'=>'logo_alt','class'=> 'form-control','value'=>!empty($web['logo_alt']) ? $web['logo_alt']:'']); ?>
            </div>
            <div class="col-sm-4">
              <label for="favicon" class="col-form-label">Favicon</label>
              <?= form_upload(['name'=>'favicon_img','class'=>'form-control','accept'=>'image/png, image/jpg, image/jpeg']); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-4">
              <?php if (!empty($web['logo_jpg'])) { ?>
              <img src="<?= base_url('uploads/') . $web['logo_jpg']; ?>" height="70px" width="150px" alt="Logo">
              <?php } ?>
            </div>
            <div class="col-sm-4">
            </div>
            <div class="col-sm-4">
              <?php if (!empty($web['favicon'])) { ?>
              <img src="<?= base_url('uploads/') . $web['favicon'] ?>" height="70px" width="150px" alt="Image">
              <?php } ?>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-12">
              <div class="custom-btn-group">
                <input type="submit" value="Submit" class="btn btn-success">
              </div>
            </div>
          </div>
          <?=form_close()?>
        </div>
      </div><br>
    </div>
  </div>
</div>