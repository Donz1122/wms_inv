<?php
if(isset($_SESSION['error'])){
  echo "
  <div class='alert alert-danger alert-dismissible row'>
  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
  <h5><i class='icon fas fa-ban'></i></h5>".$_SESSION['error']."</div>";
  unset($_SESSION['error']);
} else
if(isset($_SESSION['success'])){
  echo "
  <div class='alert alert-success alert-dismissible row'>
  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
  <h5><i class='icon fas fa-check'></i></h5>".$_SESSION['success']."</div>";
  unset($_SESSION['success']);
}
?>