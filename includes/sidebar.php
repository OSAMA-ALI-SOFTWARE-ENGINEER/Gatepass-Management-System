<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<style>
.menu-sidebar .navbar__list li { margin-bottom: 6px; }
.menu-sidebar .navbar__list li a.sidebar-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    color: #2f2f2f;
    border-radius: 8px;
    transition: all 0.2s ease;
}
.menu-sidebar .navbar__list li a.sidebar-link i {
    margin-right: 10px;
}
.menu-sidebar .navbar__list li a.sidebar-link:hover,
.menu-sidebar .navbar__list li a.sidebar-link:focus,
.menu-sidebar .navbar__list li a.sidebar-link:active {
    color: #1a73e8;
    background-color: rgba(26, 115, 232, 0.12);
    text-decoration: none;
}
.menu-sidebar .navbar__list li a.sidebar-link.is-active {
    color: #1a73e8;
    background-color: rgba(26, 115, 232, 0.15);
    font-weight: 600;
}
.menu-sidebar .navbar__list li a.sidebar-link--danger {
    color: #c62828;
}
.menu-sidebar .navbar__list li a.sidebar-link--danger:hover,
.menu-sidebar .navbar__list li a.sidebar-link--danger:focus,
.menu-sidebar .navbar__list li a.sidebar-link--danger:active {
    background-color: rgba(198, 40, 40, 0.12);
    color: #c62828;
}
.menu-sidebar .navbar__list li a.sidebar-link--danger.is-active {
    background-color: rgba(198, 40, 40, 0.16);
    font-weight: 600;
}
</style>
<aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">

                   <h1>Gate Pass</h1>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li>
                            <a href="dashboard.php" class="sidebar-link <?php echo $currentPage === 'dashboard.php' ? 'is-active' : ''; ?>">
                                <i class="fa fa-home"></i>Dashboard</a>
                        </li>

     <li>
                            <a href="selectdes.php" class="sidebar-link <?php echo $currentPage === 'selectdes.php' ? 'is-active' : ''; ?>">
                                <i class="fa fa-user-plus"></i>New Visitor</a>
                        </li>
   <li>
                            <a href="manage-newvisitors.php" class="sidebar-link <?php echo $currentPage === 'manage-newvisitors.php' ? 'is-active' : ''; ?>">
                                <i class="fa fa-users"></i>Manage Visitors</a>
                        </li>

                        <li>
                   <a href="departments.php" class="sidebar-link <?php echo $currentPage === 'departments.php' ? 'is-active' : ''; ?>">
                       <i class="fa fa-building"></i>Departments</a>
               </li>

               <li>
                        <a href="designations.php" class="sidebar-link <?php echo $currentPage === 'designations.php' ? 'is-active' : ''; ?>">
                                            <i class="fa fa-briefcase"></i>Designations</a>
              </li>

              <li>
                        <a href="addvehicle.php" class="sidebar-link <?php echo $currentPage === 'addvehicle.php' ? 'is-active' : ''; ?>">
                                            <i class="fa fa-car"></i>Add Vehicle</a>
              </li>

 <li>
                        <a href="managevehicle.php" class="sidebar-link <?php echo $currentPage === 'managevehicle.php' ? 'is-active' : ''; ?>">
                                            <i class="fa fa-gear"></i>Manage Vehicle</a>
              </li>
              <!-- <li>
                        <a href="scan_page.php" style="color: black">
                                            <i class="fa fa-gear"></i>Scanner</a>
              </li>
         <li>
                        <a href="add_visitor.php" style="color: black">
                                            <i class="fa fa-gear"></i>visitor Scanner</a>
              </li> -->
                      <li>
                            <a href="bwdates-reports.php"  class="sidebar-link sidebar-link--danger <?php echo $currentPage === 'bwdates-reports.php' ? 'is-active' : ''; ?>">
                                <i class="fas fa-copy"></i>Vistors B/w Dates</a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>
