
<style>
    .carousel-item>img{
        object-fit:fill !important;
    }
    #carouselExampleControls .carousel-inner{
        height:280px !important;
    }
</style>
<section class="py-4">
    <div class="container">
        <!-- <div class="row">
            <div class="col-md-12">
                <div id="carouselExampleControls" class="carousel slide bg-dark" data-ride="carousel" data-interval="2500">
                    <div class="carousel-inner">
                        <?php 
                            $upload_path = "uploads/banner";
                            if(is_dir(base_app.$upload_path)): 
                            $file= scandir(base_app.$upload_path);
                            $_i = 0;
                                foreach($file as $img):
                                    if(in_array($img,array('.','..')))
                                        continue;
                            $_i++;
                                
                        ?>
                        <div class="carousel-item h-100 <?php echo $_i == 1 ? "active" : '' ?>">
                            <img src="<?php echo validate_image($upload_path.'/'.$img) ?>" class="d-block w-100  h-100" alt="<?php echo $img ?>">
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    </div>
            </div>
        </div> -->
        <div class="clear-fix mb-4"></div>
        <!-- <div class="card card-outline card-primary rounded-0 shadow">
            <div class="card-body">
                <?= file_get_contents('.html') ?>
            </div>
        </div> -->
    </div>
</section>
<script>
    $(function(){
        location.href="./?p=trace_form";
        // $('#search-frm').submit(function(e){
        //     e.preventDefault();
        //     location.href = "./?"+$(this).serialize()
        // })
    })

</script>