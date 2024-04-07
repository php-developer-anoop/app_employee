<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard <?=$hospital_name?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?=base_url(ADMINPATH.'dashboard')?>">Home</a></li>
            <li class="breadcrumb-item active"> Dashboard </li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-6 block_size">
          <div class="small-box bg-danger">
            <div class="inner">
              <h4 id="case_history">000</h4>
              <p>Total Case History</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?= base_url(HOSPITALPATH. 'case-history') ?>" class="small-box-footer">More info <i
              class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function getCount( type,table ){  
    $.ajax({
      url: '<?= base_url(HOSPITALPATH.'getCount') ?>',
      type: "POST",
      data: {'type': type,'table':table},
      cache: false,
        success:function(response) {   
         $('#'+ type ).html(response);
        }
      });
    }
    function loaddatavalue(){  
  
  // setTimeout( ()=>{ getCount( 'state','state' ); },300 );
  // setTimeout( ()=>{ getCount( 'district','district' ); },400 );
  // setTimeout( ()=>{ getCount( 'block','block' ); },500 );
  // setTimeout( ()=>{ getCount( 'hospital','hospital_list' ); },600 );
  // setTimeout( ()=>{ getCount( 'employee','employee_list' ); },700 );
  // setTimeout( ()=>{ getCount( 'volunteer','volunteer_list' ); },800 );
  // setTimeout( ()=>{ getCount( 'program','program_master' ); },900 );
  // setTimeout( ()=>{ getCount( 'task','task_list' ); },1000 );
  // setTimeout( ()=>{ getCount( 'disease','disease_list' ); },1100 );
  // setTimeout( ()=>{ getCount( 'reason','reason_master' ); },1200 );
  // setTimeout( ()=>{ getCount( 'patient','patient_list' ); },1300 );
  setTimeout( ()=>{ getCount( 'case_history','case_history' ); },1400 );
  //setTimeout( ()=>{ getCount( 'contact_query','contact_query' ); },1500 );
}
  
  window.load = loaddatavalue();
</script>