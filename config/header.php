<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-light fixed-top">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
  <a href="index3.html" class="navbar-brand">
    <span class="brand-text font-weight-light">Emirates Patient Management System - PHP </span>
  </a>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <?php
      // Check if 'display_name' is set in the session
      if (isset($_SESSION['display_name'])) {
        echo '<div class="login-user text-light font-weight-bolder">Hello, ' . $_SESSION['display_name'] . '!</div>';
      } else {
        // Handle the case when 'display_name' is not set
        echo '<div class="login-user text-light font-weight-bolder">Hello, Doc!</div>';
      }
      ?>
    </li>
  </ul>
</nav>
<!-- /.navbar -->
