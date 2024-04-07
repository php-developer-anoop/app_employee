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
  </div> <br><br>
  <div class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <a href="<?= base_url(ADMINPATH . 'program-list') ?>" class="btn btn-success m-auto"
            style="float:right;position:relative;">
          View Program
          </a>
        </div>
        <div class="card-header">
          <?php echo form_open(ADMINPATH . 'save-program', 'enctype="multipart/form-data"'); ?>
          <?=form_hidden('id',$id)?>
          <div class="form-group row mt-2">
            <div class="col-sm-6">
            <?=form_label('Program Name <span class="text-danger">*</span>','program_name',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'program_name','autocomplete'=>'off', 'required' => 'required', 'placeholder' => 'Enter Program Name', 'id' => 'program_name', 'class' => 'form-control ucwords restrictedInput','value'=>$program_name]); ?>
            </div>
            <div class="col-sm-3">
            <?=form_label('Valid From <span class="text-danger">*</span>','valid_from',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'valid_from', 'required' => 'required','autocomplete'=>'off', 'placeholder' => 'YYYY-MM-DD', 'id' => 'datepicker', 'class' => 'form-control','value'=>$valid_from]); ?>
            </div>
            <div class="col-sm-3">
            <?=form_label('Valid Till <span class="text-danger">*</span>','valid_till',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'valid_till', 'required' => 'required','autocomplete'=>'off', 'placeholder' => 'YYYY-MM-DD', 'id' => 'datepicker1', 'class' => 'form-control','value'=>$valid_till]); ?>
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