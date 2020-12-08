<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

<!------ Include the above in your HEAD tag ---------->

<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
<script src="https://use.fontawesome.com/5a8a7bb461.js"></script>


<div class="container">
    <div class="row">
        <div class="col-sm-12">
        <h3><strong>Testimonial</strong></h3>
        <div class="seprator"></div>
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
              <!-- Wrapper for slides -->
              <div class="carousel-inner">
                <?php
                  
                    $args = array(
                        'post_type' => 'live-review',
                        'post_status' => 'publish',
                        'posts_per_page' => 5,
                        'meta_query' => array(     
                                 array(
                             'key' => '_live_review_key',                 
                             'value' => 's:8:"approved";i:1',                       
                             'compare' => 'LIKE'                    
                           ),

                        )
                    );

                    $portfolio = new WP_Query( $args );//var_dump($portfolio);
                    $i = 0;
                    if( $portfolio->have_posts() ) {
                         while( $portfolio->have_posts() ) 
                         {  
                            $portfolio->the_post();
                            $meta_value = get_post_meta( get_the_ID() , '_live_review_key', true );
                            $email = get_post_meta( get_the_ID(), '_live_review_key', true )['email'] ?? '';
                    ?>

                      <div class="item <?=$i==0 ? "active" : ""?>">
                  <div class="row" style="padding: 20px">
                    <button style="border: none;"><i class="fa fa-quote-left testimonial_fa" aria-hidden="true"></i></button>
                    <p class="testimonial_para"><?=get_the_content() ;?>.</p><br>
                    <div class="row">
                    <div class="col-sm-2">
                        <img src="http://demos1.showcasedemos.in/jntuicem2017/html/v1/assets/images/jack.jpg" class="img-responsive" style="width: 100%">
                        </div>
                        <div class="col-sm-10">
                        <h4><strong><?=$meta_value['name']?></strong></h4>
                        <p class="testimonial_subtitle"><span>><?=$email?></span><br>
                        <span>Officeal All Star Cafe</span>
                        </p>
                    </div>
                    </div>
                  </div>
                </div>


<?php $i++;}
                // Very Important
                  wp_reset_postdata();
                     } ?>

              
            
              </div>
            </div>
            <div class="controls testimonial_control pull-right">
                <a class="left fa fa-chevron-left btn btn-default testimonial_btn" href="#carousel-example-generic"
                  data-slide="prev"></a>

                <a class="right fa fa-chevron-right btn btn-default testimonial_btn" href="#carousel-example-generic"
                  data-slide="next"></a>
              </div>
        </div>
    </div>
</div>
