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
          <a href="<?= base_url(ADMINPATH . 'project-list') ?>" class="btn btn-success m-auto"
            style="float:right;position:relative;">
          View Project
          </a>
        </div>
        <div class="card-header">
          <?=form_open(ADMINPATH . 'save-project'); ?>
          <?=form_hidden('id',$id)?>
          <div class="form-group row mt-2">
               <?=form_label('Project Name <span class="text-danger">*</span>','project_name',['class'=>'col-sm-2 col-form-label'])?>
            <div class="col-sm-4">
              <?= form_input(['name' => 'project_name','autocomplete'=>'off', 'required' => 'required', 'placeholder' => 'Enter Project Name', 'id' => 'project_name', 'class' => 'form-control  restrictedInput','value'=>$project_name]); ?>
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
          <?=form_close()?>
        </div>
      </div>
    </div>
  </div>
</div>