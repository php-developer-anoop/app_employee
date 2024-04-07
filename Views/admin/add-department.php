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
          <a href="<?= base_url(ADMINPATH . 'department-list') ?>" class="btn btn-success m-auto"
            style="float:right;position:relative;">
          View Departments
          </a>
        </div>
        <div class="card-header">
          <?php echo form_open(ADMINPATH . 'save-department', 'enctype="multipart/form-data"'); ?>
          <?=form_hidden('id',$id)?>
          <div class="form-group row mt-2">
            <?=form_label('Department Name <span class="text-danger">*</span>','department_name',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-4">
              <?= form_input(['name' => 'department_name','autocomplete'=>'off', 'required' => 'required', 'placeholder' => 'Enter Department Name', 'id' => 'department_name', 'class' => 'form-control ucwords restrictedInput','value'=>$department_name]); ?>
            </div>
            <?=form_label('Priority <span class="text-danger">*</span>','priority',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-2">
              <?= form_input(['name' => 'priority','autocomplete'=>'off', 'required' => 'required','placeholder' => 'Enter Priority','maxlength'=>'2', 'id' => 'priority', 'class' => 'form-control notzero numbersOnlyInput','value'=>$priority]); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
            <?=form_label('Status','status',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-6">
              <div class="row mt-2">
                <div class="col-2">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="status" <?= ($status == 'Active') ? 'checked' : '' ?> type="radio" id="checkStatus1" value="Active" checked>
                    <?=form_label('Active','checkStatus1',['class'=>'custom-control-label'])?>
                  </div>
                </div>
                <div class="col-2">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="status" <?= ($status == 'Inactive') ? 'checked' : '' ?> type="radio" id="checkStatus2" value="Inactive">
                    <?=form_label('Inactive','checkStatus2',['class'=>'custom-control-label'])?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row mt-2">
            <div class="col-sm-12">
              <div class="custom-btn-group">
              <button type="submit" value="Submit" class="btn btn-success">Submit</button>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>