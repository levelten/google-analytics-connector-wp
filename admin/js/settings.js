jQuery(document).ready(function(){if(window.location.href.indexOf("page=gapwp_")!=-1){var a="basic";if(window.location.hash){a=window.location.hash.split("#")[2].split("-")[1]}else{if(window.location.href.indexOf("page=gapwp_errors_debugging")!=-1){a="errors"}}jQuery(".nav-tab-wrapper a").each(function(b){jQuery(this).removeClass("nav-tab-active");jQuery("#"+this.hash.split("#")[2]).hide()});jQuery("#tab-"+a).addClass("nav-tab-active");jQuery("#gapwp-"+a).show()}jQuery('a[href^="#"]').click(function(b){if(window.location.href.indexOf("page=gapwp_")!=-1){jQuery(".nav-tab-wrapper a").each(function(c){jQuery(this).removeClass("nav-tab-active");jQuery("#"+this.hash.split("#")[2]).hide()});jQuery(this).addClass("nav-tab-active");jQuery("#"+this.hash.split("#")[2]).show()}})});