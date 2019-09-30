<?php
  require_once('process_post.php');
  include('sidebar.php');

  $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $getURI = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $_SESSION['getURI'] = $getURI;

  $getOwnStatus = mysqli_query($mysqli, "SELECT u.id, u.firstname, u.lastname, up.user_post, up.user_location, up.date_added
    FROM user_posts up
    JOIN users u
    ON u.id = up.user_id
    WHERE up.user_id='$user_id'
    ORDER BY date_added DESC
    LIMIT 10");

  #Get User Information
  $getUserInformation = mysqli_query($mysqli, " SELECT * FROM users WHERE id = '$user_id' ");
  $newUserInformation = $getUserInformation->fetch_array();
  //Get Suggestions
  $getUsersSuggestion = mysqli_query($mysqli, "SELECT * FROM users
    WHERE (id NOT IN (SELECT from_user_id FROM user_links WHERE from_user_id = '$user_id')
    AND id NOT IN (SELECT to_user_id FROM user_links WHERE from_user_id = '$user_id'))
    AND
    (id NOT IN (SELECT from_user_id FROM user_links WHERE to_user_id = '$user_id')
    AND id NOT IN (SELECT to_user_id FROM user_links WHERE to_user_id = '$user_id'))
    AND id <> '$user_id'
    LIMIT 10");

?>
<title><?php echo $newUserInformation['firstname'].' '.$newUserInformation['lastname']; ?></title>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

<?php require('topbar.php'); ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
        <?php
        if(isset($_SESSION['message'])){?>
          <div class="alert alert-<?=$_SESSION['msg_type']?> alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            ?>
          </div>
          <?php } ?>
          <!-- Page Heading -->
          <!-- Page Heading -->
            <div class="align-items-center mb-4">
              <p style="text-align:center;">
                <button class="btn" id="asd" data-toggle="modal" data-target="#ModalChangeDP" aria-haspopup="true" aria-expanded="false"><img style="width: 10rem; height: 10rem; border-radius: 50%; border: 3px solid #17A673;" src="<?php echo  $newUserInformation['profile_image']; ?>"></button>
                <!-- Modal For Request Here -->
                <div class="modal fade" id="ModalChangeDP" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Change Display Picture</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <!-- Contents Here -->
                       <form action="process_profile.php"  method="POST" enctype="multipart/form-data">
                        <input class="" type="file" name="profile_image" accept="image/*" value="Select Photo" required>
                        <button class="btn btn-sm btn-primary" name="upload_photo">Upload</button>
                       </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="far fa-window-close"></i> Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                    <!-- End Modal For PC Equipments -->                 
                <h3 class="m-0 font-weight-bold" style="color: #1b5b3a; text-align:center;"><?php echo $newUserInformation['firstname'].' '.$newUserInformation['lastname'];?></h3>
              </p>
            </div>

          <div class="row">
            <div class="col-md-8" style="/* width: 70%;height:500px;background-color:green; */">
              <!-- Feed Container -->
              <div class="card shadow row mb-2" style="/*height:150px ; background-color: red;*/">
                <div class="card shadow">
                  <div class="card-header" style="background-color: #1b5b3a;  ">
                    <h6 class="m-0 font-weight-bold" style="color: white;">Create Post</h6>
                  </div>                  
                 <div class="card-body">
                  <div class="text-center">
                  </div>
                    <form accept-charset="UTF-8" action="process_post.php" method="post">
                      <textarea class="form-control" placeholder="Write something about your status" id="status_text" name="status_text" style="min-height: 100px; max-height: 100px;" required></textarea>
                      <br/>
                      <button type="submit" class="btn btn-sm ml-auto float-right" style="background-color: #1b5b3a; color: white;" name="status_post">POST</button>
                      <br/>
                    </form>
                </div>
                </div>
              </div>
<?php while($newOwnStatus=$getOwnStatus->fetch_assoc()){
  $getDateAdded = date_create($newOwnStatus['date_added']);
  $date_added = date_format($getDateAdded, 'F j, Y');
  $time_added = date_format($getDateAdded, 'h:i A');
  $newDateAdded = $date_added.' at '.$time_added;
  ?>
            <div class="card shadow row mb-2">
              <div class="card shadow">
                <div class="card-header">
                  <h6 class="m-0 font-weight-bold"><a href="<?php echo "link.php?linkid=".$newOwnStatus['id']; ?>" style="color: #1b5b3a;"><?php echo $newOwnStatus['firstname'].' '.$newOwnStatus['lastname'];  ?></a>
                    <span class="float-right font-weight-normal" style="font-size: 12px;"><?php echo $newDateAdded; ?></span></h6>
                </div>
                <div class="card-body">
                <!--  <div class="text-center">
                    <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_posting_photo.svg" alt="">
                  </div> -->
                  <p>
                    <?php echo  $newOwnStatus['user_post'];?>
                  </p>
                  <span style="font-size: 10px;" class="float-right">
                    <i class="far fa-compass"></i><!-- ADD LOCATION HERE --> Philippines</span>
                </div>
              </div>
            </div>
<?php } ?>
              <!-- End Feed Container -->
            </div>

            <div class="col-md-4">
                <div class="card shadow">
                <div class="card-header">
                  <h6 class="m-0 font-weight-bold" style="color: green;">Suggestions</h6>
                </div>
                <div class="card-body">
<?php while($newUsersSuggestion=$getUsersSuggestion->fetch_assoc()){ ?>                  
                  <!-- Content Suggestions -->
                    <?php include("suggestions.php"); ?>                                 
                  <!-- End Content Suggestions -->
<?php } ?>
                 <center style="font-size: 11px;">--- NOTHING FOLLOWS ---</center>                   
                </div>                
                </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
<?php
  include('footer.php');
?>