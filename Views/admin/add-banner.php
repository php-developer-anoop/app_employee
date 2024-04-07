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
          <a href="<?= base_url(ADMINPATH . 'banner-list') ?>" class="btn btn-success m-auto"
            style="float:right;position:relative;">
          View Banners
          </a>
        </div>
        <div class="card-header">
          <?=form_open_multipart(ADMINPATH . 'save-banner'); ?>
          <?=form_hidden('id',$id)?>
          <?=form_hidden('old_banner',$banner)?>
          <div class="form-group row mt-2">
            <?=form_label('App Type<span class="text-danger">*</span>','app_type',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-3">
              <select name="app_type" id="app_type" class="form-control select2" required>
                <option value="">--Select App Type--</option>
                <option value="employee" <?=!empty($app_type) && ($app_type=="employee") ?"selected":""?>>Employee</option>
                <option value="volunteer" <?=!empty($app_type) && ($app_type=="volunteer") ?"selected":""?>>Volunteer</option>
              </select>
            </div>
            <?=form_label('Banner<span class="text-danger">*</span>','banner',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-3">
              <input type="file" name="banner" id="banner" class="form-control" <?=empty($banner)?"required":""?>>
            </div>
            <?php if(!empty($banner)){?>
            <div class="col-sm-2">
              <img src="<?=base_url('uploads/'.$banner)?>" height="100" width="100">
            </div>
            <?php } ?>
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
          <?=form_close()?>
        </div>
      </div>
    </div>
  </div>
</div>