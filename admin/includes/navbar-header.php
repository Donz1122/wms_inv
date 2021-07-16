<nav class="main-header navbar navbar-expand navbar-white navbar-light bg-info">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block row">
      <?php  
        if($_SESSION['restriction'] == 0) 
          $restriction = "Admin";
        elseif($_SESSION['restriction'] == 1)
          $restriction = "Stock Clerk/Warehouseman";
        elseif($_SESSION['restriction'] == 2)
          $restriction = "Requister";
        else 
          $restriction = "Super User";

      ?>

      <!-- <a href="index.php" class="nav-link">Home</a> -->
      <a href="index.php" class="nav-link"><?= $restriction ?></a> 
      
    </li>    
  </ul>

  <ul class="navbar-nav ml-auto">
    <div class="user-panel d-flex">
      <div class="image">
        <img src="<?php echo 'files/'.$_SESSION['img'] ?>" class="img-circle" >
      </div>
      <div class="info">
        <a data-toggle="tooltip" title="Logout" href="logout.php" style="color: white;"><?=  utf8_decode($_SESSION['user']) ?></a>
      </div>
    </div>

    <li class="nav-item">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">1</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="reorderitems.php" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> Reorder Items
            <span class="float-right text-muted text-sm"><?=$_SESSION['reordercount']?></span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> soon...
            <span class="float-right text-muted text-sm">0</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> soon...
            <span class="float-right text-muted text-sm">0</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer"></a>
        </div>
      </li>      
    </li>
  </ul>
</nav>

<script type="text/javascript">
  
  

</script>

 