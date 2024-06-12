<div id="loading"></div>
<div id="page"></div>
<nav class="navbar">
  <div class="navbar-menu-wrapper">
    <ul class="navbar-nav navbar-nav-left">
      <li class="nav-item">
        <a class="nav-link" href="admindashboard.php">Dashboard</a>
      </li>
      
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdownProductManagement" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Product Management</a>
        <div class="dropdown-menu navbar-dropdown" aria-labelledby="dropdownProductManagement">
          <a class="dropdown-item" href="category.php">Manage Category</a>
          <a class="dropdown-item" href="product.php">Manage Product</a>
        </div>
      </li>
        
      <li class="nav-item dropdown">
            <a class="nav-link" href="farmprofile.php">Farm Details</a>
      </li>

      <li class="nav-item dropdown">
            <a class="nav-link" href="store.php">Farm Store</a>
          </li>
      
      <li class="nav-item">
        <a class="nav-link" href="invoices.php">Invoices</a>
      </li>
      <!-- Check if user is Admin and show Reports -->
      <?php
        $aid=$_SESSION['adminid'];
        $sql="SELECT * from  admin where ID=:aid";
        $query = $pdo -> prepare($sql);
        $query->bindParam(':aid',$aid,PDO::PARAM_STR);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        $cnt=1;
        if($query->rowCount() > 0)
        {  
            foreach($results as $row)
            { 
                if($row->role=="Admin"  )
                { 
                    ?>
          

           <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</a>
            <div class="dropdown-menu  navbar-dropdown" aria-labelledby="dropdown05">
              <a class="dropdown-item" href="product_report.php">Product Report</a>
              <a class="dropdown-item" href="stock_report.php">Stock Report</a>
               <a class="dropdown-item" href="invoice_report.php">Invoice Report</a>
            </div>
          </li>
         
        <?php 
                } 
            }
        } ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdownUserManagement" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">User Management</a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="dropdownUserManagement">
                <a class="dropdown-item" href="user_rigster.php">Register Users</a>
                <a class="dropdown-item" href="manage_users.php">Manage Users</a>    
            </div>
        </li>
    
      </ul>
    <!-- Right-aligned profile and settings -->
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item nav-profile dropdown">
        <?php
        $aid=$_SESSION['adminid'];
        $sql="SELECT * from admin where ID=:aid";
        $query = $pdo -> prepare($sql);
        $query->bindParam(':aid',$aid,PDO::PARAM_STR);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        if($query->rowCount() > 0) {
          foreach($results as $row) { ?>
            <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <div class="nav-profile-img">
                <img class="img-avatar" src="../chattels/images/profiles/<?php echo ($row->Photo=="avatar.jpg") ? "../chattels/images/profiles/custimages/avatar.jpg" : $row->Photo; ?>" alt="">
              </div>
              <div class="nav-profile-text">
                <p class="mb-1"><?php echo $row->FirstName; ?> <?php echo $row->LastName; ?></p>
              </div>
            </a>
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
