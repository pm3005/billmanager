<h1>Welcome to Bill Manager</h1>
<hr>
<div class="row">       
          <!-- /.col -->
          <div class="col-12 col-sm-4 col-md-4 col-sm-12 col-xs-12">
            <a href="<?php echo base_url ?>admin/?page=transactions&status=0">
              <div class="info-box">
                <span class="info-box-icon bg-gradient-secondary elevation-1"><i class="fas fa-truck-loading"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Pending Bills</span>
                  <span class="info-box-number">
                    <?php 
                      $cargo = $conn->query("SELECT * FROM shipment_list where `status` = 0 ")->num_rows;
                      echo format_num($cargo);
                    ?>
                    <?php ?>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
            </a>
            <!-- /.info-box -->
          </div>
          <div class="col-12 col-sm-4 col-md-4 col-sm-12 col-xs-12">
            <a href="<?php echo base_url ?>admin/?page=transactions&status=1">
              <div class="info-box">            
                <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-truck-loading"></i></span>   
                <div class="info-box-content">
                  <span class="info-box-text">Picked-Up</span>
                  <span class="info-box-number">
                    <?php 
                      $cargo = $conn->query("SELECT * FROM shipment_list where `status` = 1 ")->num_rows;
                      echo format_num($cargo);
                    ?>
                    <?php ?>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
            </a>
            <!-- /.info-box -->
          </div>
          <div class="col-12 col-sm-4 col-md-4 col-sm-12 col-xs-12">
            <a href="<?php echo base_url ?>admin/?page=transactions&status=2">
              <div class="info-box">
                <span class="info-box-icon bg-gradient-warning elevation-1"><i class="fas fa-truck-loading"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Arrived</span>
                  <span class="info-box-number">
                    <?php 
                      $cargo = $conn->query("SELECT * FROM shipment_list where `status` = 2 ")->num_rows;
                      echo format_num($cargo);
                    ?>
                    <?php ?>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
            </a>
            <!-- /.info-box -->
          </div>
          <div class="col-12 col-sm-4 col-md-4 col-sm-12 col-xs-12">
            <a href="<?php echo base_url ?>admin/?page=transactions&status=3">
              <div class="info-box">
                <span class="info-box-icon bg-gradient-light border elevation-1"><i class="fas fa-truck-loading"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Stored at Warehouse</span>
                  <span class="info-box-number">
                    <?php 
                      $cargo = $conn->query("SELECT * FROM shipment_list where `status` = 3 ")->num_rows;
                      echo format_num($cargo);
                    ?>
                    <?php ?>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
            </a>
            <!-- /.info-box -->
          </div>
          <div class="col-12 col-sm-4 col-md-4 col-sm-12 col-xs-12">
            <a href="<?php echo base_url ?>admin/?page=transactions&status=4">
              <div class="info-box">
                <span class="info-box-icon bg-gradient-blue elevation-1"><i class="fas fa-truck-loading"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Ready for Delivery</span>
                  <span class="info-box-number">
                    <?php 
                      $cargo = $conn->query("SELECT * FROM shipment_list where `status` = 4 ")->num_rows;
                      echo format_num($cargo);
                    ?>
                    <?php ?>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
            </a>
            <!-- /.info-box -->
          </div>
          <div class="col-12 col-sm-4 col-md-4 col-sm-12 col-xs-12">
            <a href="<?php echo base_url ?>admin/?page=transactions&status=5">
              <div class="info-box">
                <span class="info-box-icon bg-gradient-green elevation-1"><i class="fas fa-truck-loading"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Delivered</span>
                  <span class="info-box-number">
                    <?php 
                      $cargo = $conn->query("SELECT * FROM shipment_list where `status` = 5 ")->num_rows;
                      echo format_num($cargo);
                    ?>
                    <?php ?>
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
            </a>
            <!-- /.info-box -->
          </div>
        </div>
<!-- <div class="container">
  <?php 
    $files = array();
      $fopen = scandir(base_app.'uploads/banner');
      foreach($fopen as $fname){
        if(in_array($fname,array('.','..')))
          continue;
        $files[]= validate_image('uploads/banner/'.$fname);
      }
  ?>
  <div id="tourCarousel"  class="carousel slide" data-ride="carousel" data-interval="2500">
      <div class="carousel-inner h-100">
          <?php foreach($files as $k => $img): ?>
          <div class="carousel-item  h-100 <?php echo $k == 0? 'active': '' ?>">
              <img class="d-block w-100  h-100" style="object-fit:contain" src="<?php echo $img ?>" alt="">
          </div>
          <?php endforeach; ?>
      </div>
      <a class="carousel-control-prev" href="#tourCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#tourCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
      </a>
  </div>
</div> -->
