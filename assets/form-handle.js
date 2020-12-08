/**
 * Error catching
 */	

/**
 * Ajax form submit 
 */
jQuery(document).ready( function() {

	let reviewForm = document.getElementById('review_form');



	$('form#review_form').on('submit', function (event){
	    event.preventDefault();
	   	resetMessages();
	    let data = {
	       'name' : $('[name="name"]').val(),
	       'email' : $('[name="email"]').val(),
	       'message' : $('[name="message"]').val(),
     	};	
	    
	    if( !data.name ){
	    	reviewForm.querySelector('[data-error="invalidName"]').classList.add('show');
	    	return;
	    }
	    if( !data.validateEmail( email ) ){console.log(data.validateEmail( email ));
	    	reviewForm.querySelector('[data-error="invalidEmail"]').classList.add('show');
	    	return;
	    }
	    if( !data.message ){
	    	reviewForm.querySelector('[data-error="invalidMessage"]').classList.add('show');
	    	return;
	    }

	    var formValues= $(this).serialize();
	    $('.field-msg.js-form-submission').addClass('show');

	    jQuery.ajax({
	        type: 'POST',
	        url: jsData.ajaxurl,	
	        data: formValues,
	        success: function(result){

	        	resetMessages();

	        	if( result.status == 'success' ){console.log($('.field-msg.success.js-form-success'));

	        		reviewForm.querySelector('small.field-msg.success.js-form-success').classList.add('show');

	        	}
	        	else{//console.log(result.status);
	        		
	        		reviewForm.querySelector('.field-msg.error.js-form-error').classList.add('show');
	        	}

	        	reviewForm.reset();
	        }
	    });


	});

	function validateEmail( email ){
		let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test( String(email).toLowerCase() );
	}

	function resetMessages(){

		document.querySelectorAll('.field-msg').forEach( f => f.classList.remove('show'));

	}

	
});


