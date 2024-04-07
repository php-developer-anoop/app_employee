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
          <a href="<?= base_url(ADMINPATH . 'employee-list') ?>" class="btn btn-success m-auto"
            style="float:right;position:relative;">
          View Employee
          </a>
        </div>
        <div class="card-header">
          <?php echo form_open_multipart(ADMINPATH . 'save-employee'); ?>
          <?=form_hidden('old_profile_image',$profile_image)?>
          <?=form_hidden('id',$id)?>
          <input type="hidden" value="<?=$id?>" id="employee_id">
          <div class="form-group row mt-2">
            <div class="col-sm-3">
              <?=form_label('Employee Full Name <span class="text-danger">*</span>','full_name',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'full_name','autocomplete'=>'off','required' => 'required', 'placeholder' => 'Enter Employee Full Name', 'id' => 'full_name', 'class' => 'form-control ucwords restrictedInput','value'=>$full_name]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Date Of Joining(MM/DD/YYYY) <span class="text-danger">*</span>','datepicker2',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'date_of_joining','autocomplete'=>'off','required' => 'required', 'placeholder' => 'MM/DD/YYYY', 'id' => 'datepicker2', 'class' => 'form-control datpic','value'=>$date_of_joining]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Email <span class="text-danger">*</span>','email_id',['class'=>'col-form-label'])?>
              <?= form_input(['type'=>'email','autocomplete'=>'off','required' =>'required','onkeyup'=>'return checkDuplicateEmployee(this.value)','name' => 'email_id', 'placeholder' => 'Enter Email', 'id' => 'email_id', 'class' => 'form-control emailInput','value'=>$email_id]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Mobile Number <span class="text-danger">*</span>','mobile_no',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'mobile_no','autocomplete'=>'off','required' => 'required','maxlength'=>'10','placeholder' => 'Enter Mobile Number', 'id' => 'mobile_no', 'class' => 'form-control notzero numbersWithZeroOnlyInput','value'=>$mobile_no]); ?>
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
              <?=form_label('Project Duration(In Years) <span class="text-danger">*</span>','project_duration',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'project_duration','autocomplete'=>'off','required' => 'required', 'placeholder' => 'Enter Project Duration','maxlength'=>'2', 'id' => 'project_duration', 'class' => 'form-control numbersWithZeroOnlyInput','value'=>$project_duration]); ?>
          </div>
          <div class="col-sm-3">
              <?=form_label('Select Payroll Type <span class="text-danger">*</span>','payroll_type',['class'=>'col-form-label'])?>
              <select name="payroll_type" class="form-control select2" required>
                  <option value="">--Select Type--</option>
                  <option value="Payroll" <?=!empty($payroll_type) && ($payroll_type=='Payroll')?"selected":""?>>Payroll</option>
                  <option value="Consultant" <?=!empty($payroll_type) && ($payroll_type=='Consultant')?"selected":""?>>Consultant</option>
              </select>
          </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-3">
              <?=form_label('Designation <span class="text-danger">*</span>','designation',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'designation','autocomplete'=>'off','required' => 'required', 'placeholder' => 'Enter designation', 'id' => 'designation', 'class' => 'form-control ucwords restrictedInput','value'=>$designation]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Latitude','latitude',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'latitude','autocomplete'=>'off', 'placeholder' => 'Enter Latitude', 'id' => 'latitude', 'class' => 'form-control','value'=>$latitude]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Longitude','longitude',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'longitude', 'autocomplete'=>'off','placeholder' => 'Enter Longitude', 'id' => 'longitude', 'class' => 'form-control','value'=>$longitude]); ?>
            </div>
             <div class="col-sm-3">
              <?=form_label('Select Reporting Manager','reporting_manager',['class'=>'col-form-label'])?>
              <select name="reporting_manager" class="form-control select2">
                  <option value="">--Select Reporting Manager--</option>
                  <?php if(!empty($manager_list)){foreach($manager_list as $key=>$value){?>
                  <option value="<?=$value['id'].','.$value['full_name']?>" <?=!empty($manager_id) && ($manager_id==$value['id'])?"selected":""?>><?=$value['full_name']?></option>
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
            <?=form_label('Select Project <span class="text-danger">*</span>','project',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-3">
              <select name="project" class="form-control select2" required>
              <option value="">--Select Project--</option>
              <?php if(!empty($project_list)){foreach($project_list as $pkey=>$pvalue){?>
              <option value="<?=$pvalue['id'].','.$pvalue['project_name']?>" <?=!empty($project_id) && ($project_id==$pvalue['id'])?"selected":""?>><?=$pvalue['project_name']?></option>
              <?php }} ?>
             </select>
          </div>
          </div>
          <div class="form-group row mt-2">
            <?=form_label('Status','status',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-6">
              <div class="row mt-2">
                <div class="col-2">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="profile_status" <?= ($profile_status == 'Active') ? 'checked' : '' ?> type="radio" id="checkStatus1" value="Active" checked>
                    <?=form_label('Yes','checkStatus1',['class'=>'custom-control-label'])?>
                  </div>
                </div>
                <div class="col-2">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="profile_status" <?= ($profile_status == 'Inactive') ? 'checked' : '' ?> type="radio" id="checkStatus2" value="Inactive">
                    <?=form_label('No','checkStatus2',['class'=>'custom-control-label'])?>
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