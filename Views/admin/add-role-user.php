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
          <a href="<?= base_url(ADMINPATH . 'role-user-list') ?>" class="btn btn-success m-auto"
            style="float:right;position:relative;">
          View Users
          </a>
        </div>
        <div class="card-header">
          <?=form_open(ADMINPATH . 'save-role-user'); ?>
          <?=form_hidden('id',$id)?>
          <div class="form-group row mt-2">
            <div class="col-sm-3">
              <?=form_label('User Name <span class="text-danger">*</span>','user_name',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'user_name','autocomplete'=>'off','required' => 'required', 'placeholder' => 'Enter User Name', 'id' => 'user_name', 'class' => 'form-control ucwords restrictedInput','value'=>$user_name]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('User Email <span class="text-danger">*</span>','user_email',['class'=>'col-form-label'])?>
              <?= form_input(['type'=>'email','autocomplete'=>'off','onblur'=>'return checkDuplicateUser(this.value)','required' => 'required','name' => 'user_email', 'placeholder' => 'Enter User Email', 'id' => 'user_email', 'class' => 'form-control emailInput','value'=>$user_email]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('User Phone <span class="text-danger">*</span>','user_phone',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'user_phone','autocomplete'=>'off','required' => 'required','maxlength'=>'10','placeholder' => 'Enter User Phone', 'id' => 'user_phone', 'class' => 'form-control notzero numbersWithZeroOnlyInput','value'=>$user_phone]); ?>
            </div>
            <div class="col-sm-3">
              <?=form_label('Select Department <span class="text-danger">*</span>','department',['class'=>'col-form-label'])?>
              <select name="department" id="department" required class="form-control select2">
                <option value="">--Select Department--</option>
                <?php if(!empty($department_list)){foreach($department_list as $key=>$value){?>
                <option value="<?=$value['id'].','.$value['department_name']?>" <?=!empty($department_id)&&$value['id']==$department_id?"selected":""?>><?=$value['department_name']?></option>
                <?php }} ?>
              </select>
            </div>
          </div>
          <div class="col-sm-3">
              <?=form_label('Change Password','password',['class'=>'col-form-label'])?>
              <?= form_input(['type'=>'password','autocomplete'=>'off','name' => 'password','placeholder' => 'Enter Password', 'id' => 'password', 'class' => 'form-control']); ?>
            </div>
          <div class="form-group row mt-2">
            <?=form_label('Status','status',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-6">
              <div class="row mt-2">
                <div class="col-3">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="status" <?= ($status == 'Active') ? 'checked' : '' ?> type="radio" id="checkStatus1" value="Active" checked>
                    <?=form_label('Active','checkStatus1',['class'=>'custom-control-label'])?>
                  </div>
                </div>
                <div class="col-3">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="status" <?= ($status == 'Inactive') ? 'checked' : '' ?> type="radio" id="checkStatus2" value="Inactive">
                    <?=form_label('Inactive','checkStatus2',['class'=>'custom-control-label'])?>
                  </div>
                </div>
                <div class="col-3">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="status" <?= ($status == 'Blocked') ? 'checked' : '' ?> type="radio" id="checkStatus3" value="Blocked">
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
          <?=form_close()?>
        </div>
      </div>
    </div>
  </div>
</div>