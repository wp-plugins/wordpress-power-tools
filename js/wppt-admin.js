jQuery(document).ready(function(){
	jQuery("#showDefaultOptions").click(function(){
		jQuery("tr.defaultOptions").show();
		jQuery("#hideDefaultOptions").show();
		jQuery(this).hide();
		return false;
	})
	jQuery("#hideDefaultOptions").click(function(){
		jQuery("tr.defaultOptions").hide();
		jQuery("#showDefaultOptions").show();
		jQuery(this).hide();
		return false;
	})
	jQuery(".wppt_deleteOption").click(function(){
    var answer = confirm("Are you sure you want to delete this option? This is likely NOT REVERSIBLE!");
      if (answer) {
         return true;
      }else{
         return false;
      }		
	});
});
