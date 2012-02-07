jQuery(document).ready(function(){
  jQuery("#google-tracking-profile, label[for=google-tracking-profile]").hide();
	jQuery("#show-default-options").click(function(){
		jQuery("tr.default-options").show();
		jQuery("#hide-default-options").show();
		jQuery(this).hide();
		return false;
	})
	jQuery("#hide-default-options").click(function(){
		jQuery("tr.default-options").hide();
		jQuery("#show-default-options").show();
		jQuery(this).hide();
		return false;
	})
	jQuery(".WPPT-delete-option").click(function(){
    var answer = confirm("Are you sure you want to delete this option? This is likely NOT REVERSIBLE!");
      if (answer) {
         return true;
      } else {
         return false;
      }		
	});
	jQuery(".WPPT-delete-option").click(function(){
    var answer = confirm("Are you sure you want to delete this option? This is likely NOT REVERSIBLE!");
      if (answer) {
         return true;
      } else {
         return false;
      }		
	});
	jQuery('#google-tracking').click(function(){
    if (this.checked) {
      jQuery("#google-tracking-profile, label[for=google-tracking-profile]").show();
    } else {
      jQuery("#google-tracking-profile, label[for=google-tracking-profile]").hide();
    }
  });
  

  
  if((jQuery('#google-tracking').attr("checked"))){
    jQuery("#google-tracking-profile, label[for=google-tracking-profile]").show();
    } else {
    jQuery("#google-tracking-profile, label[for=google-tracking-profile]").hide();
    }
});  

