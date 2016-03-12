        <div class="container-fluid">
        	<div class="span6">
            	<ul class="leftTopNav topNavmenu">
            	   <li>
	                   	<a href="<?php echo base_url() ?>">
	                    	<img src="<?php echo base_url() ?>assets/img/expoCRM-logo.png" alt="expocrm logo" />
	                    </a>
                   </li>
            	</ul>
            </div>
            <div class="span6">
	          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </a>
           <?php if($this->session->userdata(SESS_UNAME)):?>
          <div class="btn-group">
                <ul class="topNavmenu">
                    <li class="logout">
                    <a href="<?php echo base_url(); ?>user/auth/logout/">Sign Out</a>
                    </li>
                    <li class="setting">
                    <a href="#" id="settings">Setting</a>
                    </li>
                    <li class="logedinUser">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                          <?php echo $this->session->userdata(SESS_UNAME);?></span>
                        </a>
                    </li>
            	    <li class="home">
                      <a href="<?php echo base_url() ?>" class="home">Home</a>
                    </li>
                </ul>
          </div>
	      <?php endif;?>
          <div class="nav-collapse">
<!--            <ul class="nav">
              <li class="dashboard"><a href="#">Dashboard</a></li>
              <li class="reports"><a href="#">Reports</a></li>
              <li class="setting"><a href="#">Setting</a></li>
            </ul>-->
          </div><!--/.nav-collapse -->
          <div class="nav_menus">
              
              
          </div>
        </div>
  </div> 