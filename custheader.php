<div id="loading"></div>
<div id="page"></div>
<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="navbar-menu-wrapper d-flex align-items-stretch w-100">
    <ul class="navbar-nav navbar-nav-left">
    <div class="logo">
            <!-- Farm logo and name -->
            <a class="navbar-brand brand-logo" href="#welcome-section">
              <img src="../chattels/images/farmimages/favicn.jpeg" alt="Farm Logo">
              <span>ECHO-CHICK FARM</span> <!-- Farm Name Next to the Logo -->
            </a>
        </div>
      <li class="nav-item">
        <a class="nav-link" href="customerdashboard.php">Dashboard</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="about.php">About Us</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="buy.php">Buy</a>

      </li>               
       <li><a class="nav-link" href="faq.php">FAQs</a></li>
        <li><a class="nav-link" href="contact.php">Contact Us</a></li>
      </li>

</ul>
    <!-- Right-aligned profile and settings -->
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item nav-profile dropdown">
        <?php
        $aid=$_SESSION['userid'];
        $sql="SELECT * from customer where id=:aid";
        $query = $pdo -> prepare($sql);
        $query->bindParam(':aid',$aid,PDO::PARAM_STR);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        if($query->rowCount() > 0) {
          foreach($results as $row) { ?>
            <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
            <div class=" nav-profile"> 
            <div class="nav-profile-img">
                <img class="img-avatar" src="../chattels/images/profiles/<?php echo ($row->Photo=="avatar.jpg") ? "../chattels/images/profiles/custimages/avatar.jpg" : $row->Photo; ?>" alt="">
              </div>
              <div class="nav-profile-text">
                <p class="mb-1"><?php echo $row->FirstName; ?> <?php echo $row->LastName; ?></p>
              </div>
            </a>
          </div>
            <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="profile.php">
                <i class="mdi mdi-account mr-2 text-success"></i> Profile
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="change_password.php">
                <i class="mdi mdi-settings mr-2 text-success"></i> Change Password
              </a>
              <div class="dropdown-divider"></div>
              
              <a class="dropdown-item" href="../logout.php">
                <i class="mdi mdi-logout mr-2 text-danger"></i> Logout
              </a>
            </div>
          </li>
        <?php
          }
        }
        ?>
      </li>
    </ul>
    
  </div>
</nav>
