</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" id="pushmenu" data-widget="pushmenu" href="javascript:void(0)" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <?= anchor(base_url(ADMINPATH . 'logout'), '<i class="fas fa-sign-out-alt"></i> Logout', ['class' => 'nav-link']) ?>
      </li>
      <?php $session=session();
      if ($session->has('login_data')) {
        $loginData = $session->get('login_data');
      if($loginData['user_type']=="Role User"){?>
      <div class="btn-group">
      <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52" aria-expanded="false">
      <i class="fas fa-user"></i>
      </button>
      <div class="dropdown-menu" role="menu" style="">
        <a href="<?=base_url(ADMINPATH.'view-profile')?>" class="dropdown-item d-none">View Profile</a>
        <a href="<?=base_url(ADMINPATH.'change-password')?>" class="dropdown-item">Change Password</a>
      </div>
      <?php } }?>
      <li class="navitem">
        <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>