<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a href="<?= base_url(ADMINPATH . 'dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item active">
              <?= $title_name ?>
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
          <a href="<?= base_url(ADMINPATH . 'notification-list') ?>" class="btn btn-success m-auto"
            style="float:right;position:relative;">
          View Notifications
          </a>
        </div>
        <div class="card-header">
          <?=form_open_multipart(ADMINPATH . 'save-notification'); ?>
          <?=form_hidden('id',$id)?>
          <?=form_hidden('old_image',$image)?>
          <div class="form-group row mt-2">
          <div class="col-sm-3">
          <?=form_label('App Type <span class="text-danger">*</span>','app_type',['class'=>'col-form-label'])?>
            <select name="app_type" id="app_type" class="form-control select2" required>
                <option value="">--Select App Type--</option>
                <option value="employee" <?=!empty($app_type) && ($app_type=="employee") ?"selected":""?>>Employee</option>
                <option value="volunteer" <?=!empty($app_type) && ($app_type=="volunteer") ?"selected":""?>>Volunteer</option>
            </select>
            </div>
            <div class="col-sm-9">
            <?=form_label('Title <span class="text-danger">*</span>','title',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'title', 'required' => 'required', 'placeholder' => 'Enter Title', 'id' => 'title', 'class' => 'form-control ucwords restrictedInput','value'=>$title]); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
          <?=form_label('Description <span class="text-danger">*</span>','description',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-10">
              <?= form_textarea(['name' => 'description', 'required' => 'required', 'placeholder' => 'Enter description','cols'=>'5','rows'=>'3','id' => 'description', 'class' => 'form-control','value'=>$description]); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
            <?=form_label('Image <span class="text-danger">*</span>','image',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-3">
            <input type="file" name="image" id="image" class="form-control" <?=empty($image)?"required":""?>>
           
            </div>
            <?php if(!empty($image)){?>
            <div class="col-sm-2">
              <img src="<?=base_url('uploads/'.$image)?>" height="100" width="100">
            </div>
            <?php } ?>
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