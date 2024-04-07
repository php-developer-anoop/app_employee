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
          <a href="<?= base_url(ADMINPATH . 'volunteer-list') ?>" class="btn btn-success m-auto"
            style="float:right;position:relative;">
          View Volunteer
          </a>
        </div>
        <div class="card-header">
          <?php echo form_open_multipart(ADMINPATH . 'save-volunteer'); ?>
          <?=form_hidden('old_profile_image',$profile_image)?>
          <?=form_hidden('id',$id)?>
          <input type="hidden" id="volunteer_id" value="<?=$id?>">
          <div class="form-group row mt-2">
            <div class="col-sm-3">
              <?=form_label('Volunteer Full Name','full_name',['class'=>'col-form-label'])?><span class="text-danger">*</span>
              <?= form_input(['name' => 'full_name','autocomplete'=>'off', 'required' => 'required', 'placeholder' => 'Enter Volunteer Full Name', 'id' => 'full_name', 'class' => 'form-control ucwords restrictedInput','value'=>$full_name]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Email','email_id',['class'=>'col-form-label'])?><span class="text-danger">*</span>
              <?= form_input(['type'=>'email','autocomplete'=>'off','required' => 'required','onkeyup'=>'return checkDuplicateVolunteer(this.value)','name' => 'email_id', 'placeholder' => 'Enter Email', 'id' => 'email_id', 'class' => 'form-control emailInput','value'=>$email_id]); ?>
            </div>
            <div class="col-sm-2">
              <?=form_label('DOB(MM/DD/YYYY)','date_of_birth',['class'=>'col-form-label'])?><span class="text-danger">*</span>
              <?= form_input(['name' => 'date_of_birth', 'required' => 'required','autocomplete'=>'off', 'placeholder' => 'MM/DD/YYYY', 'id' => 'datepicker2', 'class' => 'form-control','value'=>$date_of_birth]); ?>
            </div>
            <div class="col-sm-2">
              <?=form_label('Mobile Number','mobile_no',['class'=>'col-form-label'])?><span class="text-danger">*</span>
              <?= form_input(['name' => 'mobile_no','autocomplete'=>'off','required' => 'required','maxlength'=>'10','placeholder' => 'Enter Mobile Number', 'id' => 'mobile_no', 'class' => 'form-control notzero numbersWithZeroOnlyInput','value'=>$mobile_no]); ?>
            </div>
            <div class="col-sm-2">
                <?=form_label('Select Gender','gender',['class'=>'col-form-label'])?><span class="text-danger">*</span>
                <select name="gender" id="gender" class="form-control select2" required>
                    <option value="">Select Gender</option>
                    <option value="Male" <?=!empty($gender) && ($gender=="Male")?"selected":""?>>Male</option>
                    <option value="Female" <?=!empty($gender) && ($gender=="Female")?"selected":""?>>Female</option>
                    <option value="Other" <?=!empty($gender) && ($gender=="Other")?"selected":""?>>Other</option>
                </select>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-3">
              <?=form_label('Address','address',['class'=>'col-form-label'])?><span class="text-danger">*</span>
              <?= form_input(['name' => 'address','autocomplete'=>'off','required' => 'required', 'placeholder' => 'Enter Address', 'id' => 'address', 'class' => 'form-control','value'=>$address]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Pincode','pincode',['class'=>'col-form-label'])?><span class="text-danger">*</span>
              <?= form_input(['name' => 'pincode', 'autocomplete'=>'off','required' => 'required','placeholder' => 'Enter Pincode','maxlength'=>'6', 'id' => 'pincode', 'class' => 'form-control numbersWithZeroOnlyInput','value'=>$pincode]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Latitude','latitude',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'latitude','autocomplete'=>'off', 'placeholder' => 'Enter Latitude', 'id' => 'latitude', 'class' => 'form-control','value'=>$latitude]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Longitude','longitude',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'longitude','autocomplete'=>'off', 'placeholder' => 'Enter Longitude', 'id' => 'longitude', 'class' => 'form-control','value'=>$longitude]); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-3">
              <?=form_label('States','state',['class'=>'col-form-label'])?><span class="text-danger">*</span>
              <select name="state" required id="state" class="form-control select2" onchange="getDistricts(this.value,'<?=$district_id.','.$district_name?>')">
                <option value="">--Select State--</option>
                <?php if(!empty($state_list)){foreach($state_list as $key=>$value){?>
                <option value="<?=$value['id'].','.$value['state_name']?>" <?=!empty($value['id'] && $state_id==$value['id'])?"selected":""?>><?=$value['state_name']?></option>
                <?php }} ?>
              </select>
            </div>
            <div class="col-sm-3">
              <?=form_label('Districts','district',['class'=>'col-form-label'])?><span class="text-danger">*</span>
              <select name="district" required id="district" class="form-control select2" onchange="getBlock(this.value,'<?=$block_id.','.$block_name?>')">
                <option value="">--Select District--</option>
              </select>
            </div>
            <div class="col-sm-3">
              <?=form_label('Blocks','block',['class'=>'col-form-label'])?>
              <select name="block" id="block" class="form-control select2" >
                <option value="">--Select Block--</option>
              </select>
            </div>
            <div class="col-sm-3">
              <?=form_label('Employee','employee',['class'=>'col-form-label'])?><span class="text-danger">*</span>
              <select name="employee" required id="employee" class="form-control select2">
                <option value="">--Select Employee--</option>
                <?php if(!empty($employee_list)){foreach($employee_list as $key=>$value){?>
                <option value="<?=$value['id'].','.$value['full_name']?>" <?=!empty($value['id'] && $employee_id==$value['id'])?"selected":""?>><?=$value['full_name']?></option>
                <?php }} ?>
              </select>
            </div>
          </div>
          <div class="form-group row mt-2">
            <?=form_label('Profile Image','profile_image',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-3">
              <?= form_upload(['name' => 'profile_image','id' => 'profile_image', 'class' => 'form-control']); ?>
            </div>
            <?php if(!empty($profile_image)){?>
            <div class="col-sm-2">
              <img src="<?=base_url('uploads/'.$profile_image)?>" height="100" width="100">
            </div>
            <?php } ?>
          </div>
          <div class="form-group row mt-2">
            <?=form_label('Status','status',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-6">
              <div class="row mt-2">
                <div class="col-3">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="profile_status" <?= ($profile_status == 'Active') ? 'checked' : '' ?> type="radio" id="checkStatus1" value="Active" checked>
                    <?=form_label('Active','checkStatus1',['class'=>'custom-control-label'])?>
                  </div>
                </div>
                <div class="col-3">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="profile_status" <?= ($profile_status == 'Inactive') ? 'checked' : '' ?> type="radio" id="checkStatus2" value="Inactive">
                    <?=form_label('Inactive','checkStatus2',['class'=>'custom-control-label'])?>
                  </div>
                </div>
                <div class="col-3">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="profile_status" <?= ($profile_status == 'Blocked') ? 'checked' : '' ?> type="radio" id="checkStatus3" value="Blocked">
                    <?=form_label('Blocked','checkStatus3',['class'=>'custom-control-label'])?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-12">
              <div class="custom-btn-group">
              <button type="submit" value="Submit" class="btn btn-success" id="submit">Submit</button>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div><br>
</div>