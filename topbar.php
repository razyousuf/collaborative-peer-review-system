  <style>
    .user-img {
        border-radius: 50%;
        height: 25px;
        width: 25px;
        object-fit: cover;
    }
	/* Badge position and styling */
	#notificationIcon {
		position: relative;
	}

	#notificationCount {
		position: absolute;
		top: 0px;
		right: 0px;
		font-size: 12px;
		color: white;
	}
  </style>
<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-primary navbar-dark ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <?php if(isset($_SESSION['login_id'])): ?>
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="" role="button"><i class="fas fa-bars"></i></a>
      </li>
    <?php endif; ?>
      <li>
        <a class="nav-link text-white"  href="./" role="button"> <large><b><?php echo $_SESSION['system']['name'] ?></b></large></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
     
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
	  <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notification Icon -->
        <li class="nav-item">
            <a class="nav-link" href="#" id="notificationIcon" role="button">
                <i class="far fa-bell"></i>
                <!-- Display notification count badge here -->
				<span class="badge badge-danger" id="notificationCount">0</span>
            </a>
        </li>
     <li class="nav-item dropdown">
            <a class="nav-link"  data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
              <span>
                <div class="d-felx badge-pill">
                  <span class=""><img src="assets/uploads/<?php echo $_SESSION['login_avatar'] ?>" alt="" class="user-img border "></span>
                  <span><b><?php echo ucwords($_SESSION['login_firstname']. ' '.$_SESSION['login_lastname']) ?></b></span>
                  <span class="fa fa-angle-down ml-2"></span>
                </div>
              </span>
            </a>
            <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
              <a class="dropdown-item" href="javascript:void(0)" id="manage_account"><i class="fa fa-cog"></i> Manage Account</a>
              <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
            </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <script>
     $('#manage_account').click(function(){
        uni_modal('Manage Account','manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
      })
  </script>

<script>
        $(document).ready(function() {
            function checkNotifications() {
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    data: { action: 'check_notifications' },
                    success: function(response) {
                        let data = JSON.parse(response);
                        if (data.success) {
                            let count = data.count || 0;
                            $('#notificationCount').text(count);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching notifications: " + error);
                    }
                });
            }

            // Call checkNotifications periodically if needed or on specific events
            checkNotifications();
            setInterval(checkNotifications, 300000); // checks every 5 minutes
        });
    </script>