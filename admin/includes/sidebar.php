
<div id="showalert"></div>

<aside class="main-sidebar sidebar-dark-primary elevation-2" style="position: fixed;">
  <a href="index.php" class="brand-link bg-info">
    <img src="../dist/img/zaneco3x3.png" alt="ZANECO Logo" class="brand-image-xl img-circle"
    style="opacity: .8">
    <span class="brand-text font-weight-heavy"><span style="font-size: 24px;">ZANECO</span> WMS</span>
  </a>

  <div class="sidebar">

    <nav class="mt-1">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-flat" data-widget="treeview" role="menu" data-accordion="false">
        <!-- <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-flat" data-widget="treeview" role="menu" data-accordion="true"> -->

         <li class="nav-item has-treeview" id="sb_otherinfo">
          <a href="#" class="nav-link" id="sb_otherinfo_b">
            <i class="nav-icon fas fa-toolbox"></i>
            <p>Sources<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="itemindex.php" class="nav-link" id="sb_itemindex"><i class="nav-icon fa fa-list"></i><p>Item Index</p></a>
            </li>
            <?php if((intval($_SESSION['restriction']) <= 1) || intval($_SESSION['restriction']) == 101) { ?>
             <li class="nav-item">
              <a href="suppliers.php" class="nav-link" id="sb_suppliers"><i class="fas fa-truck-monster nav-icon"></i><p>Supplier</p></a>
            </li>
            <li class="nav-item">
              <a href="department.php" class="nav-link" id="sb_department"><i class="fas fa-umbrella-beach nav-icon"></i><p>Department</p></a>
            </li>
            <li class="nav-item">
              <a href="poles.php" class="nav-link" id="sb_poles"><i class="fas fa-university nav-icon"></i><p>Poles</p></a>
            </li>
          <?php } ?>
          <?php if((intval($_SESSION['restriction']) == 101)) { ?>
            <li class="nav-item">
              <a href="#" class="nav-link" id="sb_users" onclick="navlink_user()"><i class="fas fa-clipboard-check nav-icon"></i><p>User Accounts</p></a>
            </li>
            <li class="nav-item">
              <a href="signatories.php" class="nav-link" id="sb_signs"><i class="fas fa-user nav-icon"></i><p>Signatories</p></a>
            </li>
          <?php } ?>
        </ul>
      </li>

      <li class="nav-item has-treeview" id="sb_materials">
        <a href="#" class="nav-link" id="sb_materials_b">
          <i class="nav-icon fas fa-tools"></i>
          <p>Materials<i class="right fas fa-angle-left"></i></p>
        </a>
        <ul class="nav nav-treeview">
          <?php if((intval($_SESSION['restriction']) <= 1) || intval($_SESSION['restriction']) == 101) { ?>
            <li class="nav-item">
              <a href="receipts.php" class="nav-link" id="sb_receipts"><i class="nav-icon fas fa-train"></i><p>Material Receipts</p></a>
            </li>
          <?php } ?>
          <li class="nav-item">
            <a href="issuance.php" class="nav-link" id="sb_issuance"><i class="nav-icon fas fa-tram"></i><p>Material Issuance</p></a>
          </li>
          <li class="nav-item">
            <?php if((intval($_SESSION['restriction']) <= 1) || intval($_SESSION['restriction']) == 101) { ?>
              <a href="memoreceipts.php" class="nav-link" id="sb_memoreceipts"><i class="nav-icon fas fa-trophy"></i><p>Memorandum Receipts</p></a>
            <?php } ?>
          </li>
        </ul>
      </li>

      <?php if((intval($_SESSION['restriction']) <= 1) || intval($_SESSION['restriction']) == 101) { ?>
        <li class="nav-item has-treeview " id="sb_verification">
          <a href="#" class="nav-link" id=sb_verification_b>
            <i class="nav-icon fas fa fa-check"></i>
            <p>Verification<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="reorderitems.php" class="nav-link" id="sb_reorderlist"><i class="fas fa-fw fa-list nav-icon"></i>
                <p>
                  Reorder Items
                  <span class="right badge badge-warning"><?= $_SESSION['reordercount'] ?></span>
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="transferredmr.php" class="nav-link" id="sb_transferredmr"><i class="fas fa-arrow-left nav-icon"></i><p>Transferred MR</p></a>
            </li>
            <li class="nav-item">
              <a href="returntowarehouse.php" class="nav-link" id="sb_returntowarehouse"><i class="fas fa-step-forward nav-icon"></i><p>Returned To Warehouse</p></a>
            </li>
            <li class="nav-item">
              <a href="returntosupplier.php" class="nav-link" id="sb_returntosupplier"><i class="fas fa-step-backward nav-icon"></i><p>Returned To Supplier</p></a>
            </li>
            <li class="nav-item">
              <a href="salvagematerials.php" class="nav-link" id="sb_salvagematerials"><i class="fas fa-viruses nav-icon"></i><p>Salvage Materials</p></a>
            </li>
            <?php //if(intval($_SESSION['restriction']) == 101) { ?>
              <li class="nav-item">
                <a href="warranty_item.php" class="nav-link" id="sb_warranty"><i class="fas fa-house-damage nav-icon"></i>
                  <p>
                    Warranty Items
                    <span class="right badge badge-success"><?= $_SESSION['expirethisyear'] ?></span>
                  </p>
                </a>
              </li>
              <?php //} ?>
            </ul>
          </li>

        <?php } ?>

        <?php if((intval($_SESSION['restriction']) == 101)) { ?>
          <li class="nav-item has-treeview " id="sb_maintenance">
            <a href="#" class="nav-link" id=sb_maintenance_b>
              <i class="nav-icon fas fa fa-cogs"></i>
              <p>Maintenance<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="userlogs.php" class="nav-link" id="sb_userlogs"><i class="fas fa-fw fa-list nav-icon"></i>
                  <p>
                    User Logs
                    <span class="right badge badge-info">x</span>
                  </p>
                </a>
              </li>

            </ul>
          </li>
        <?php } ?>

        <li class="nav-item has-treeview " id="sb_history">
          <a href="#" class="nav-link" id=sb_history_b>
            <i class="nav-icon fas fa fa-history"></i>
            <p>History<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="equipment_history.php" class="nav-link" id="sb_history1"><i class="fas fa-fw fa-utensils nav-icon"></i>
                <p>
                  Equipment History
                  <span class="right badge badge-warning">!</span>
                </p>
              </a>
            </li>

          </ul>
        </li>

      </ul>
    </nav>
  </div>
</aside>

<script type="text/javascript">
  var res       = "<?php echo $_SESSION['restriction']; ?>";

  setInterval(function(){
    $.ajax({
      url     : 'datahelpers/alert_helper.php',
      type    : 'post',
      dataType: 'json',
      data    : { search:'ok' },
      success: function(data){
        if(Number(data.count) > 0 ) {
          showalert(data.id, data.remarks, data.by, data.area);
          remove_alerted(data.index);
        }
      },
      error: function(data){
      },
    });
  }, 3000);

  function remove_alerted(index) {
   $.ajax({
    url     : 'datahelpers/alert_helper.php',
    type    : 'post',
    dataType: 'json',
    data    : { remove:index },
    success: function(data) {  }
  });
 }

 function showalert(id,remarks, by, area){
  $(document).Toasts('create', {
    class    : 'bg-white',
    title    : '<span style="font-size: 12px;"> Material Request </span>',
    subtitle : '<a href="issuancedetails.php?id='+id+'" class="nav-link" id="">'+id+'</a>',
    position : 'bottomRight',
    icon     : 'fas fa-envelope fa-lg',
    body     : '<span style="font-size:13px;">'+remarks+'<span><strong><hr>' + '<span style="font-size:11px;">'+ by +' - '+ area +'</span></strong>',
    allowToastClose: true,
  })
};


function navlink_user(e){
  if(Number(res) == 101 ) {
    window.location="useraccount.php";
  } else  {
    alert("Viewing only!");
  }
};

</script>
