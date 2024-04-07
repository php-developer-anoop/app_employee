<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a href="<?= base_url(ADMINPATH . 'dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item active">
              <?= $page_title ?>
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
          <a href="<?= base_url(ADMINPATH . 'cms-list') ?>" class="btn btn-success m-auto"
            style="float:right;position:relative;">
          View Cms Pages
          </a>
        </div>
        <div class="card-header">
          <?=form_open(ADMINPATH . 'save-cms'); ?>
          <?=form_hidden('id',$id)?>
          <div class="form-group row mt-2">
            <div class="col-sm-4">
              <?=form_label('Page Type <span class="text-danger">*</span>','page_type',['class'=>'col-form-label'])?>
              <select name="page_type" id="page_type" required class="form-control select2">
                <option value="">--Select Page Type--</option>
                <option value="about_us" <?=!empty($page_type) && $page_type=="about_us"?"selected":""?>>About Us</option>
                <option value="terms" <?=!empty($page_type) && $page_type=="terms"?"selected":""?>>Terms</option>
                <option value="privacy_policy" <?=!empty($page_type) && $page_type=="privacy_policy"?"selected":""?>>Privacy Policy</option>
              </select>
            </div>
            <div class="col-sm-8">
              <?=form_label('Title','title',['class'=>'col-form-label'])?>
              <?= form_input(['name' => 'title', 'placeholder' => 'Enter Title', 'id' => 'title', 'class' => 'form-control','value'=>$title]); ?>
            </div>
          </div>
          <div class="form-group row mt-2">
          
          <div class="col-sm-12">
              <?=form_label('Description','description',['class'=>'col-form-label'])?>
            <textarea name="description" id="htmeditor"><?=$description?></textarea>
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