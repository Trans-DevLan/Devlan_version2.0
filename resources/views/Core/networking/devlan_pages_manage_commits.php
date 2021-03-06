<?php
    session_start();
    include('assets/configs/config.php');
    include('assets/configs/checklogin.php');
    check_login();
    $user_id = $_SESSION['user_id'];
    //delete commits
    if(isset($_GET['delete_commit']))
    {
            $id=intval($_GET['delete_commit']);
            $adn="DELETE FROM projects WHERE project_id=?";
            $stmt= $mysqli->prepare($adn);
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $stmt->close();	   
            if($stmt)
            {
                $success = "Commit Deleted!";
            }
            else
            {
                $err = "Please try again later";
            }
            
            

    }

?>
<!DOCTYPE html>
<html lang="en">
    
    <?php include("assets/_partials/head.php");?>

    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <?php include("assets/_partials/navbar.php");?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
                <?php include("assets/_partials/sidebar.php");?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <?php
                //Use logged in user session['user_id']
                $user_id = $_SESSION['user_id'];
                $ret="SELECT  * FROM  users WHERE user_id=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('i',$user_id);
                $stmt->execute() ;//ok
                $res=$stmt->get_result();

                while($row=$res->fetch_object())
                {
            ?>
                <div class="content-page">
                    <div class="content">

                        <!-- Start Content-->
                        <div class="container-fluid">
                            
                            <!-- start page title -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="page-title-box">
                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Devlan</a></li>
                                                <li class="breadcrumb-item"><a href="devlan_pages_nw_commits_dashboard.php">Dashboard</a></li>
                                                <li class="breadcrumb-item"><a href="devlan_pages_manage_commits.php">My Commits</a></li>
                                                <li class="breadcrumb-item active">Manage Commits</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">@<?php echo $row->username;?>
                                            <?php
                                                //invoke a crazy function to make a joke from DevLanner commits
                                                $user_id = $_SESSION['user_id'];
                                                $result ="SELECT count(*)  FROM  projects WHERE  user_id = ? ";
                                                $stmt = $mysqli->prepare($result);
                                                $stmt->bind_param('i', $user_id);
                                                $stmt->execute();
                                                $stmt->bind_result($user_commits);
                                                $stmt->fetch();
                                                $stmt->close();
                                                
                                                //lets get this shit done
                                                    if($user_commits == 0 && $user_commits <= 5)
                                                        {
                                                            echo "You Are Too Mean...Please Share Something With Us"; 
                                                        }

                                                    else
                                                    {
                                                        echo "Bravo! This Are Your Commits So Far";
                                                    }
                                            ?>
                                        </h4>
                                    </div>
                                </div>
                            </div>     
                            <!-- end page title --> 

                            <div class="row">
                                <div class="col-12">
                                    <div class="card-box">
                                        <h4 class="header-title">My Commits</h4>
                                        

                                        <div class="mb-2">
                                            <div class="row">
                                                <div class="col-12 text-sm-center form-inline">
                                                    <div class="form-group mr-2" style="display:none">
                                                        <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                            <option value="">Show all</option>
                                                            <option value="active">Active</option>
                                                            <option value="disabled">Disabled</option>
                                                            <option value="suspended">Suspended</option>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="table-responsive">
                                            <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                                <thead>
                                                <tr>
                                                    <th data-toggle="true">Commit Name</th>
                                                    <th>Commit Number</th>
                                                    <th data-hide="phone">Date Commited</th>
                                                    <th data-hide="phone, tablet">Commit Category</th>
                                                    <th data-hide="phone, tablet">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                            //get logged in user commits
                                                            $user_id = $_SESSION['user_id'];
                                                            $ret="SELECT  * FROM  projects WHERE  user_id = ?  ORDER BY `projects`.`date_created` DESC LIMIT 15 ";
                                                            $stmt= $mysqli->prepare($ret) ;
                                                            $stmt->bind_param('i',$user_id);
                                                            $stmt->execute() ;//ok
                                                            $res=$stmt->get_result();

                                                        while($row=$res->fetch_object())
                                                        {
                                                            //trim timestamps to DD-YY-MMMM
                                                                $DT = $row->date_created;
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row->project_name;?></td>
                                                            <td><?php echo $row->project_number;?></td>
                                                            <td><?php echo date("d-M-Y", strtotime($DT));?></td>
                                                            <td><?php echo $row->project_category;?></td>
                                                            <td>
                                                                <a href="devlan_pages_modify_single_commit.php?project_id=<?php echo $row->project_id;?>">
                                                                    <span class="btn label-table btn-outline-success btn-sm">
                                                                        <i class="fa fa-edit"></i> <i class="mdi mdi-source-pull mr-1"></i>
                                                                            Modify Commit
                                                                    </span>
                                                                </a> 

                                                                <a href="devlan_pages_manage_commits.php?delete_commit=<?php echo $row->project_id;?>">
                                                                    <span class="btn label-table btn-outline-danger btn-sm">
                                                                        <i class="fa fa-trash"></i> <i class="mdi mdi-source-pull mr-1"></i>
                                                                            Delete Commit
                                                                    </span>
                                                                </a>   
                                                            </td>
                                                        </tr>

                                                    <?php }?>
                                                </tbody>
                                                <tfoot>
                                                <tr class="active">
                                                    <td colspan="5">
                                                        <div class="text-right">
                                                            <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div> <!-- end .table-responsive-->
                                    </div> <!-- end card-box -->
                                </div> <!-- end col -->
                            </div>
                            <!-- end row -->

                        </div> <!-- container -->

                    </div> <!-- content -->

                    <!-- Footer Start -->
                    <?php include("assets/_partials/footer.php");?>
                    <!-- end Footer -->

                </div>

            <?php }?>
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Footable js -->
        <script src="assets/libs/footable/footable.all.min.js"></script>

        <!-- Init js -->
        <script src="assets/js/pages/foo-tables.init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>