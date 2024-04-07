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
          <a href="<?= base_url(ADMINPATH . 'hospital-list') ?>" class="btn btn-success m-auto"
            style="float:right;position:relative;">
          View Hospital
          </a>
        </div>
        <div class="card-header">
          <?php echo form_open(ADMINPATH . 'save-hospital'); ?>
          <input type="hidden" name="id" id="id" value="<?=$id?>">
          <div class="form-group row mt-2">
            <div class="col-sm-3">
              <?=form_label('Hospital Name <span class="text-danger">*</span>','hospital_name',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'hospital_name','autocomplete'=>'off','required' => 'required', 'placeholder' => 'Enter Hospital Name', 'id' => 'hospital_name', 'class' => 'form-control ucwords restrictedInput','value'=>$hospital_name]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Email <span class="text-danger">*</span>','email_id',['class'=>'col-form-label'])?>
              <?= form_input(['type'=>'email','required' => 'required','autocomplete'=>'off','name' => 'email_id', 'placeholder' => 'Enter Email','onblur'=>'return checkDuplicate()', 'id' => 'email_id', 'class' => 'form-control emailInput','value'=>$email_id]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Mobile Number <span class="text-danger">*</span>','mobile_no',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'mobile_no','required' => 'required','autocomplete'=>'off','placeholder' => 'Enter Mobile Number','maxlength'=>'10', 'id' => 'mobile_no', 'class' => 'form-control notzero numbersOnly numbersWithZeroOnlyInput','value'=>$mobile_no]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Address <span class="text-danger">*</span>','address',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'address','required' => 'required','autocomplete'=>'off','placeholder' => 'Enter Address', 'id' => 'address', 'class' => 'form-control','value'=>$address]); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-3">
              <?=form_label('States <span class="text-danger">*</span>','state',['class'=>'col-form-label'])?>
              <select name="state" id="state" class="form-control select2" required onchange="getDistricts(this.value,'<?=$district_id.','.$district_name?>')">
                <option value="">--Select State--</option>
                <?php if(!empty($state_list)){foreach($state_list as $key=>$value){?>
                <option value="<?=$value['id'].','.$value['state_name']?>" <?=!empty($value['id'] && $state_id==$value['id'])?"selected":""?>><?=$value['state_name']?></option>
                <?php }} ?>
              </select>
            </div>
            <div class="col-sm-3">
              <?=form_label('Districts <span class="text-danger">*</span>','district',['class'=>'col-form-label'])?>
              <?php $block = !empty($block_id) && !empty($block_name) ? $block_id . ',' . $block_name : '';?>
              <select name="district" id="district" class="form-control select2" required onchange="getBlock(this.value,'<?=$block?>')">
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
              <?=form_label('Pincode <span class="text-danger">*</span>','pincode',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'pincode','required' => 'required','autocomplete'=>'off','placeholder' => 'Enter Pincode','maxlength'=>'6', 'id' => 'pincode', 'class' => 'form-control numbersWithZeroOnlyInput','value'=>$pincode]); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
          <div class="col-sm-3">
              <?=form_label('Reporting Manager <span class="text-danger">*</span>','employee',['class'=>'col-form-label'])?>
              <select name="employee" id="employee" class="form-control select2" required>
                <option value="">--Select Manager--</option>
                <?php if(!empty($employee_list)){foreach($employee_list as $ekey=>$evalue){?>
                  <option value="<?=$evalue['id'].','.$evalue['full_name']?>" <?=!empty($employee_id) && ($employee_id==$evalue['id'])?'selected':''?>><?=$evalue['full_name']?></option>
                <?php }} ?>
              </select>
            </div>
          <div class="col-sm-3">
              <?=form_label('Category <span class="text-danger">*</span>','category',['class'=>'col-form-label'])?>
              <select name="category" id="category" class="form-control select2" required>
                <option value="">--Select Category--</option>
                <?php if(!empty($category_list)){foreach($category_list as $ckey=>$cvalue){?>
                  <option value="<?=$cvalue['id']?>" <?=!empty($category) && ($category==$cvalue['id'])?'selected':''?>><?=$cvalue['category_name']?></option>
                <?php }} ?>
              </select>
            </div>
            <div class="col-sm-3">
              <?=form_label('Latitude','latitude',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'latitude','autocomplete'=>'off','placeholder' => 'Enter Latitude', 'id' => 'latitude', 'class' => 'form-control','value'=>$latitude]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Longitude','longitude',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'longitude','autocomplete'=>'off','placeholder' => 'Enter Longitude', 'id' => 'longitude', 'class' => 'form-control','value'=>$longitude]); ?>
            </div>
            <div class="col-sm-3">
            <?=form_label('Signing Date <span class="text-danger">*</span>','signing_date',['class'=>'col-form-label'])?>
              <?= form_input(['type'=>'date','name' => 'signing_date', 'required' => 'required','autocomplete'=>'off', 'placeholder' => 'YYYY-MM-DD', 'id' => '', 'class' => 'form-control','value'=>$signing_date]); ?>
            </div>
            <div class="col-sm-3">
            <?=form_label('Expiry Date <span class="text-danger">*</span>','expiry_date',['class'=>'col-form-label'])?>
              <?= form_input(['type'=>'date','name' => 'expiry_date', 'required' => 'required','autocomplete'=>'off', 'placeholder' => 'YYYY-MM-DD', 'id' => '', 'class' => 'form-control','value'=>$expiry_date]); ?>
            </div>
            <div class="col-sm-3">
            <?=form_label('Date Of MOU <span class="text-danger">*</span>','date_of_mou',['class'=>'col-form-label'])?>
              <?= form_input(['type'=>'date','name' => 'date_of_mou', 'required' => 'required','autocomplete'=>'off', 'placeholder' => 'YYYY-MM-DD', 'id' => '', 'class' => 'form-control','value'=>$date_of_mou]); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-12">
              <?=form_label('Scope Of Services (Multiple) <span class="text-danger">*</span>','disease_ids',['class'=>'col-form-label'])?> 
              <select name="disease_ids[]" id="disease_ids" class="form-control select2" multiple required>
                <?php if(!empty($service_list)){foreach($service_list as $dkey=>$dvalue){?>
                <option value="<?=$dvalue['id']?>" <?=(!empty($service_ids) && (in_array($dvalue['id'],$service_ids)))?"selected":""?>><?=$dvalue['service_name']?></option>
                <?php }} ?>
              </select>
            </div>
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
                <button type="submit" value="Submit" id="submit" class="btn btn-success" >Submit</button>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div><br>
</div>