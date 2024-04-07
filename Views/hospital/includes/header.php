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
        <?= anchor(base_url(HOSPITALPATH . 'logout'), '<i class="fas fa-sign-out-alt"></i> Logout', ['class' => 'nav-link']) ?>
      </li>
      <div class="btn-group">
      <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52" aria-expanded="false">
      <i class="fas fa-user"></i>
      </button>
      <div class="dropdown-menu" role="menu" style="">
        <a href="<?=base_url(HOSPITALPATH.'view-profile')?>" class="dropdown-item d-none">View Profile</a>
        <a href="<?=base_url(HOSPITALPATH.'change-password')?>" class="dropdown-item">Change Password</a>
      </div>
      <li class="navitem">
        <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>