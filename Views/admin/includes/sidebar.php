<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?=base_url(ADMINPATH.'dashboard')?>" class="brand-link">
  <?php $imgattr=['src'=>$logo,'alt'=>!empty($company['logo_alt'])?$company['logo_alt']:"",'class'=>'brand-image img-circle elevation-3','style'=>'opacity:.8'];?>
  <?=img($imgattr)?>
  <span class="brand-text font-weight-light"><?=!empty($company['company_name'])?$company['company_name']:""?></span>
  </a>
  <div class="sidebar">
    <div class="user-panel pt-2 d-flex">
      <div class="info text-white">
        <?php 
          $session=session();
          $username = '';
          $role = '';
          if ($session->has('login_data')) {
           $loginData = $session->get('login_data');
           
           if (isset($loginData['role_user_name'])) {
               $username = $loginData['role_user_name'];
           }
           if (isset($loginData['role'])) {
               $role = $loginData['role'];
           }
          }?>
        <h5>
        <?= $username;?></h5>
        <h6 class="text-uppercase"><?=$role;?></h6>
      </div>
    </div>
    <?php 
      $read_menuArray =[];
      $write_menuArray = [];
          if($loginData['user_type']!="Super Admin"){
            $department_id = $loginData['department'];
            $data_assign = getSingle("department",'read_menu_ids,write_menu_ids',['status'=>'Active','id'=>$department_id]);
            $read_menuArray = explode(',',($data_assign['read_menu_ids']));
            $write_menuArray = explode(',',($data_assign['write_menu_ids']));
          }
          
            $menuList = getData("menus",'*',['status'=>'Active']);
        ?>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php $uri = service('uri');
          $url = $uri->getSegment(2) ?? 'dashboard';
          ?>
          <li class="nav-item">
              <a href="<?= base_url(ADMINPATH).'dashboard';?>" class="nav-link <?=$url=="dashboard"?"active":""?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url(ADMINPATH).'attendance-dashboard';?>" class="nav-link <?=$url=="attendance-dashboard"?"active":""?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Attendance Dashboard</p>
              </a>
            </li>
        <?php 
          if($loginData['user_type']!="Super Admin"){
            if(!empty($menuList)){
            foreach ($menuList as $key => $value) {
             if( $value['menu_type'] == 'Menu'){
            
             if(in_array($value['id'], $read_menuArray)) { ?>
        <li class="nav-item">
          <a href="<?= !empty($value['slug']) ? base_url(ADMINPATH).$value['slug'] : '#';?>" class="nav-link">
            <i class="nav-icon fas fa-bars"></i>
            <p>
              <?= $value['menu_title'];?>
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <?php $subMenuList = getSubMenuList( $menuList, $value['id'] ); 
            if( !empty( $subMenuList)){ ?>
          <ul class="nav nav-treeview" style="display: none;">
            <?php  
              foreach ($subMenuList as $key => $value1) { ?> 
            <li class="nav-item">
              <a href="<?= !empty($value1['slug']) ? base_url(ADMINPATH).$value1['slug'] : '#';?>" class="nav-link <?=$url==$value1['slug']?"active":""?>">
                <i class="far fa-circle nav-icon"></i>
                <p><?= $value1['menu_title'];?></p>
              </a>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } 
          } } } }else{?>
        <?php 
          if(!empty($menuList)){
          foreach ($menuList as $key => $value) {
           if( $value['menu_type'] == 'Menu'){
          
          ?>
        <li class="nav-item">
          <a href="<?= !empty($value['slug']) ? base_url(ADMINPATH).$value['slug'] : '#';?>" class="nav-link">
            <i class="nav-icon fas fa-bars"></i>
            <p>
              <?= $value['menu_title'];?>
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <?php $subMenuList = getSubMenuList( $menuList, $value['id'] ); 
            if( !empty( $subMenuList)){ ?>
          <ul class="nav nav-treeview" style="display: none;">
            <?php  
              foreach ($subMenuList as $key => $value1) { ?> 
            <li class="nav-item">
              <a href="<?= !empty($value1['slug']) ? base_url(ADMINPATH).$value1['slug'] : '#';?>" class="nav-link <?=$url==$value1['slug']?"active":""?>">
                <i class="far fa-circle nav-icon"></i>
                <p><?= $value1['menu_title'];?></p>
              </a>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php 
          } } } }?>
      </ul>
    </nav>
  </div>
</aside>