<?php
  echo "<script>
  const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 4000});
      </script>";
  if(isset($_SESSION['error'])){
  echo "<script>
    Toast.fire({
      icon: 'error',
      title: '".$_SESSION['error']."'});
    </script>";    
    unset($_SESSION['error']);
  }

  if(isset($_SESSION['success'])){
  echo "<script>
    Toast.fire({
      icon: 'success',
      title: '".$_SESSION['success']."'});    
    </script>";
    unset($_SESSION['success']);
  }
  ?>