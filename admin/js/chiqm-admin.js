
const CHIQM_SUCCESS = 0;
const CHIQM_ERROR = 1;

jQuery(document).ready( function( $ ) {

  $('#wpbody-content').on("click",".app-status a",function($this){

		var astat = $(this.closest('.app-status'));
		
		var data = {'action':'chiqm_toggle_app_status','slug':astat.data('id'),'status':astat.data('status')};
		$.post(chiqm.ajaxurl,data,function(response){
			//alert(response);
			var resp = JSON.parse(response);
			if(resp['chiqm_status'] == CHIQM_SUCCESS){
				astat.find('a').html(resp['chiqm_val']);
				astat.data('status',resp['chiqm_val']);
			} 
		});
  });

  $('#wpbody-content').on("click","#button-save-custom-appdir",function($this){

		var cappdir = $('#chiqm-custom-appdir');
		
		var data = {'action':'chiqm_update_custom_appdir','custom_appdir':cappdir.val()};
		$.post(chiqm.ajaxurl,data,function(response){
			var resp = JSON.parse(response);
			if(resp['chiqm_status'] == CHIQM_SUCCESS){
				$('#button-save-custom-appdir').val('Saved');
				$('#chiqm-msg-appdir').html('<font color="green">Successfully saved.</font>');
			} else {
				$('#button-save-custom-appdir').val('Update');
				$('#chiqm-msg-appdir').html('<font color="red">'+resp['chiqm_val']+'</font>');
			}
		});
		
  });

  $('#wpbody-content').on("click","#button-save-custom-appurl",function($this){

		var cappurl = $('#chiqm-custom-appurl');
		
		var data = {'action':'chiqm_update_custom_appurl','custom_appurl':cappurl.val()};
		$.post(chiqm.ajaxurl,data,function(response){
			var resp = JSON.parse(response);
			if(resp['chiqm_status'] == CHIQM_SUCCESS){
				$('#button-save-custom-appurl').val('Saved');
				$('#chiqm-msg-appurl').html('<font color="green">Successfully saved.</font>');
			} else {
				$('#button-save-custom-appurl').val('Update');
				$('#chiqm-msg-appurl').html('<font color="red">'+resp['chiqm_val']+'</font>');
			}

		});
  });
});


