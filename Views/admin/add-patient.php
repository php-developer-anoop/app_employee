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
          <a href="<?= base_url(ADMINPATH . 'patient-list') ?>" class="btn btn-success m-auto"
            style="float:right;position:relative;">
          View Patient
          </a>
        </div>
        <div class="card-header">
          <?php echo form_open_multipart(ADMINPATH . 'save-patient'); ?>
          <?=form_hidden('old_profile_image',$profile_image)?>
          <?=form_hidden('old_aadhaar_front_image',$aadhaar_front_image)?>
          <?=form_hidden('old_aadhaar_back_image',$aadhaar_back_image)?>
          <?=form_hidden('id',$id)?>
          <input type="hidden" value="<?=$id?>" id="patient_id">
          <div class="form-group row mt-2">
            <div class="col-sm-3">
              <?=form_label('Patient Full Name <span class="text-danger">*</span>','full_name',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'full_name','autocomplete'=>'off','required' => 'required', 'placeholder' => 'Enter Patient Full Name', 'id' => 'full_name', 'class' => 'form-control ucwords restrictedInput','value'=>$full_name]); ?>
            </div>
            <div class="col-sm-2">
              <?=form_label('Mobile Number <span class="text-danger">*</span>','mobile_no',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'mobile_no','autocomplete'=>'off','onblur'=>'return checkDuplicatePatient(this.value)','required' => 'required','maxlength'=>'10','placeholder' => 'Enter Mobile Number', 'id' => 'mobile_no', 'class' => 'form-control notzero numbersWithZeroOnlyInput','value'=>$mobile_no]); ?>
            </div>
            <div class="col-sm-2">
              <?=form_label('DOB(MM/DD/YYYY) <span class="text-danger">*</span>','date_of_birth',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'date_of_birth', 'autocomplete'=>'off','required' => 'required','placeholder' => 'MM/DD/YYYY', 'id' => 'datepicker2', 'class' => 'form-control datpic','value'=>$date_of_birth]); ?>
            </div>
            <div class="col-sm-2">
              <?=form_label('Gender <span class="text-danger">*</span>','gender',['class'=>'col-form-label'])?>
              <select name="gender" id="gender" class="form-control select2" required>
                <option value="">--Select Gender--</option>
                <option value="Male" <?=!empty($gender) && ($gender=="Male")?"selected":""?>>Male</option>
                <option value="Female" <?=!empty($gender) && ($gender=="Female")?"selected":""?>>Female</option>
                <option value="Other" <?=!empty($gender) && ($gender=="Other")?"selected":""?>>Other</option>
              </select>
            </div>
            <div class="col-sm-3">
              <?=form_label('Occupation','occupation',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'occupation','autocomplete'=>'off','placeholder' => 'Enter Occupation', 'id' => 'occupation', 'class' => 'form-control ucwords restrictedInput','value'=>$occupation]); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-3">
              <?=form_label('States <span class="text-danger">*</span>','state',['class'=>'col-form-label'])?>
              <select name="state" required id="state" class="form-control select2" onchange="getDistricts(this.value,'<?=$district_id.','.$district_name?>')">
                <option value="">--Select State--</option>
                <?php if(!empty($state_list)){foreach($state_list as $key=>$value){?>
                <option value="<?=$value['id'].','.$value['state_name']?>" <?=!empty($value['id'] && $state_id==$value['id'])?"selected":""?>><?=$value['state_name']?></option>
                <?php }} ?>
              </select>
            </div>
            <div class="col-sm-3">
              <?=form_label('Districts <span class="text-danger">*</span>','district',['class'=>'col-form-label'])?>
              <select name="district" required id="district" class="form-control select2" onchange="getBlock(this.value,'<?=$block_id.','.$block_name?>')">
                <option value="">--Select District--</option>
              </select>
            </div>
            <div class="col-sm-3">
              <?=form_label('Blocks','block',['class'=>'col-form-label'])?>
              <select name="block"  id="block" class="form-control select2">
                <option value="">--Select Block--</option>
              </select>
            </div>
            <div class="col-sm-3">
              <?=form_label('Address <span class="text-danger">*</span>','address',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'address','autocomplete'=>'off','required' => 'required', 'placeholder' => 'Enter Address', 'id' => 'address', 'class' => 'form-control','value'=>$address]); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-2">
              <?=form_label('Pincode <span class="text-danger">*</span>','pincode',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'pincode','autocomplete'=>'off','required' => 'required', 'placeholder' => 'Enter Pincode','maxlength'=>'6', 'id' => 'pincode', 'class' => 'form-control numbersWithZeroOnlyInput','value'=>$pincode]); ?>
            </div>
            <div class="col-sm-4">
              <?=form_label('Hospital <span class="text-danger">*</span>','hospital',['class'=>'col-form-label'])?>
              <select name="hospital" id="hospital_name" class="form-control select2" required>
                <option value="">--Select Hospital--</option>
                <?php if(!empty($hospital_list)){foreach($hospital_list as $hkey=>$hvalue){?>
                <option value="<?=$hvalue['id'].','.$hvalue['hospital_name']?>" <?=!empty($hospital_id)&&($hospital_id==$hvalue['id'])?"selected":""?>><?=$hvalue['hospital_name']?></option>
                <?php }} ?>
              </select>
            </div>
            <div class="col-sm-3">
              <?=form_label('Employee','employee',['class'=>'col-form-label'])?>
              <select name="employee" id="employee_name" class="form-control select2" onchange="getEmployee(this.value,'<?=$volunteer_id.','.$volunteer_name?>')">
                <option value="">--Select Employee--</option>
                <?php if(!empty($employee_list)){foreach($employee_list as $ekey=>$evalue){?>
                <option value="<?=$evalue['id'].','.$evalue['full_name']?>" <?=!empty($employee_id && $employee_id==$evalue['id'])?"selected":""?>><?=$evalue['full_name']?></option>
                <?php }} ?>
              </select>
            </div>
            <div class="col-sm-3">
              <?=form_label('Volunteer','volunteer',['class'=>'col-form-label'])?>
              <select name="volunteer" id="volunteer_name" class="form-control select2">
                <option value="">--Select Volunteer--</option>
              </select>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-3">
              <?=form_label('Aadhaar Number','aadhaar_number',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'aadhaar_number','autocomplete'=>'off','maxlength'=>'12','placeholder' => 'Enter Aadhaar Number', 'id' => 'aadhaar_number', 'class' => 'form-control notzero numbersWithZeroOnlyInput','value'=>$aadhaar_number]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Disease/Checkup <span class="text-danger">*</span>','disease',['class'=>'col-form-label'])?>
              <select name="disease" id="disease_name" class="form-control select2" required >
                <option value="">--Select Disease--</option>
                <?php if(!empty($disease_list)){foreach($disease_list as $dkey=>$dvalue){?>
                <option value="<?=$dvalue['id'].','.$dvalue['disease_name']?>" <?=!empty($disease_id)&&($disease_id==$dvalue['id'])?"selected":""?>><?=$dvalue['disease_name']?></option>
                <?php }} ?>
              </select>
            </div>
          </div>
          <div class="form-group row mt-2">
            <?=form_label('Profile Image','profile_image',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-3">
              <?php  $required_profile_image = empty($profile_image) ? 'required' : ''; ?>
              <input type="file" name="profile_image" class="form-control" id="profile_image" >
            </div>
            <?php if(!empty($profile_image)){?>
            <div class="col-sm-2">
              <img src="<?=base_url('uploads/'.$profile_image)?>" height="100" width="100">
            </div>
            <?php } ?>
          </div>
          <div class="form-group row mt-2">
            <?=form_label('Aadhaar Front Image','aadhaar_front_image',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-3">
              <?php  $required_aadhaar_front_image = empty($aadhaar_front_image) ? 'required' : ''; ?>
              <input type="file" name="aadhaar_front_image" class="form-control" id="aadhaar_front_image" >
            </div>
            <?php if(!empty($aadhaar_front_image)){?>
            <div class="col-sm-2">
              <img src="<?=base_url('uploads/'.$aadhaar_front_image)?>" height="100" width="100">
            </div>
            <?php } ?>
          </div>
          <div class="form-group row mt-2">
            <?=form_label('Aadhaar Back Image','aadhaar_back_image',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-3">
              <?php  $required_aadhaar_back_image = empty($aadhaar_back_image) ? 'required' : ''; ?>
              <input type="file" name="aadhaar_back_image" class="form-control" id="aadhaar_back_image" >
            </div>
            <?php if(!empty($aadhaar_back_image)){?>
            <div class="col-sm-2">
              <img src="<?=base_url('uploads/'.$aadhaar_back_image)?>" height="100" width="100">
            </div>
            <?php } ?>
          </div>
          <?php /* ?>
          <div class="form-group row mt-2">
            <?=form_label('Status','status',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-6">
              <div class="row mt-2">
                <div class="col-3">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="status" <?= ($status == 'add') ? 'checked' : '' ?> type="radio" id="checkStatus1" value="add" checked>
                    <?=form_label('Added','checkStatus1',['class'=>'custom-control-label'])?>
                  </div>
                </div>
                <div class="col-3">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="status" <?= ($status == 'convert') ? 'checked' : '' ?> type="radio" id="checkStatus2" value="convert">
                    <?=form_label('Converted','checkStatus2',['class'=>'custom-control-label'])?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php  */?>
          <div class="form-group row mt-2">
            <div class="col-sm-12">
              <div class="custom-btn-group">
                <button type="submit" value="Submit" id="submit" class="btn btn-success">Submit</button>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div><br>
</div>