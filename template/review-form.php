<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script> -->
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<!------ Include the above in your HEAD tag ---------->

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<div class="container">
    <h2 class="text-center">Contact Form</h2>
	<div class="row justify-content-center">
		<div class="col-12 col-md-8 col-lg-12 pb-5">

            <!--Form with header-->
            <form action="#" method="post" data-url="<?=admin_url('wp-ajax.php')?>" id="review_form">
                <div class="card border-primary rounded-0">
                    <div class="card-header p-0">
                        <div class="bg-info text-white text-center py-2">
                            <h3><i class="fa fa-envelope"></i> Contactanos</h3>
                            <p class="m-0">Con gusto te ayudaremos</p>
                        </div>
                    </div>
                    <div class="card-body p-3">

                        <!--Body-->
                        <div class="form-group">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-user text-info"></i></div>
                                </div>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nombre y Apellido" >
                                
                            </div>
                            <small class="field-msg error" data-error="invalidName" >Your Name is Required</small>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-envelope text-info"></i></div>
                                </div>
                                <input type="text" class="form-control" id="email" name="email" placeholder="ejemplo@gmail.com" >
                                
                            </div>
                            <small class="field-msg error"  data-error="invalidEmail">Your Email is Required</small>
                        </div>

                        <div class="form-group">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-comment text-info"></i></div>
                                </div>
                                <textarea class="form-control" placeholder="Envianos tu Mensaje" name="message" 
                                id="message" ></textarea>
                                
                            </div>
                            <small class="field-msg error" data-error="invalidMessage" >Your Message is Required</small>
                        </div>

                        <div class="text-center">
                            <input type="submit" value="submit" class="submit btn btn-info btn-block rounded-0 py-2" name="submit" id="review_form_button">
                            <input type="hidden" name="action" value="live_review_submit" />
                            <input type="hidden" name="nounce" value="<?php wp_create_nonce('live_review_nounce') ?>" />
                            <small class="field-msg js-form-submission" >Submission in process, please wait&hellip;</small>
                            <small class="field-msg success js-form-success" >Message Successfully submitted, thank you!</small>
                            <small class="field-msg error js-form-error"  >There was a problem with the Contact Form, please try again!</small>
                        </div>
                    </div>

                </div>
            </form>
            <!--Form with header-->

        </div>
	</div>
</div>