<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?=base_url(HOSPITALPATH.'dashboard')?>" class="brand-link">
  <?php $imgattr=['src'=>$logo,'alt'=>!empty($company['logo_alt'])?$company['logo_alt']:"",'class'=>'brand-image img-circle elevation-3','style'=>'opacity:.8'];?>
  <?=img($imgattr)?>
  <span class="brand-text font-weight-light"><?=!empty($company['company_name'])?$company['company_name']:""?></span>
  </a>
  <div class="sidebar">
    <?php $uri = service('uri');
      if ($uri->setSilent()->getSegment(2)) {
         $url = $uri->setSilent()->getSegment(2);
      } else {
         $url = 'dashboard';
      }
      ?>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="<?= base_url(HOSPITALPATH . 'dashboard') ?>" class="nav-link <?=(!empty($url) && $url == 'dashboard') ? "active " : "";?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?=base_url(HOSPITALPATH . 'case-history') ?>"class="nav-link <?=(!empty($url) && $url == 'case-history') ? "active " : "";?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Case History</p>
          </a>
        </li>
        <?php /*
        <li class="nav-item <?=(!empty($url) && ($url == 'add-slider' || $url == 'view-slider')) ? "menu-open" : "";?>">
          <a href="#" class="nav-link <?=(!empty($url) && ($url == 'add-slider' || $url == 'view-slider')) ? "active" : "";?>">
            <i class="fas fa-angle-left right"></i>
            <p> Home Page Master <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?=base_url(HOSPITALPATH . 'add-slider') ?>"class="nav-link <?=(!empty($url) && $url == 'add-slider') ? "active " : "";?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Home Page Slider</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?=base_url(HOSPITALPATH . 'view-slider') ?>" class="nav-link <?=(!empty($url) && $url == 'view-slider') ? "active " : "";?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Home Page Slider List</p>
              </a>
            </li>
          </ul>
        </li> */?>
      </ul>
    </nav>
  </div>
</aside>