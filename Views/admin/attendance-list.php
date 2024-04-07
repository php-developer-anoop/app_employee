<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a href="<?= base_url(ADMINPATH . 'dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item active">
              <?= $title; ?>
            </li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <input type="hidden" value="0" id="totalRecords" />
          <table id="responseData" class="table table-bordered mb-0">
              <div class="d-flex justify-content-end mb-2">
              <a href="<?=$url?>">
              <button class="btn btn-success btn-sm position-relative"><i class="fa fa-download"></i></button>
              </a>
            </div>
            <thead>
              <tr>
                <th class="sorting">User Type</th>
                <th class="sorting">Full Name</th>
                <th class="sorting">Mobile Number</th>
                <th class="sorting">Email</th>
                <th class="sorting">District</th>
                <th class="sorting">State</th>
              </tr>
            </thead>
            <tbody>
                <?php if(!empty($workforce)){foreach($workforce as $key=>$value){?>
              <tr class="odd">
                <td><?=$value['user_type']?></td>
                <td><?=$value['full_name']?></td>
                <td><?=$value['mobile_no']?></td>
                <td><?=$value['email_id']?></td>
                <td><?=$value['district_name']?></td>
                <td><?=$value['state_name']?></td>
              </tr>
           <?php }} ?>
            </tbody>
          </table>
        </div>
      </div>
      <br>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
            // Initialize DataTable with ID
            $('#responseData').DataTable();
        });
</script>