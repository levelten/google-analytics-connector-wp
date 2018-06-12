<?php
/**
 * Copyright 2018 Alin Marcu
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
?>
<script>
var gacwpDnt = false;
var gacwpProperty = '<?php echo $data['uaid']?>';
var gacwpDntFollow = <?php echo $data['gaDntOptout'] ? 'true' : 'false'?>;
var gacwpOptout = <?php echo $data['gaOptout'] ? 'true' : 'false'?>;
var disableStr = 'ga-disable-' + gacwpProperty;
if(gacwpDntFollow && (window.doNotTrack === "1" || navigator.doNotTrack === "1" || navigator.doNotTrack === "yes" || navigator.msDoNotTrack === "1")) {
	gacwpDnt = true;
}
if (gacwpDnt || (document.cookie.indexOf(disableStr + '=true') > -1 && gacwpOptout)) {
	window[disableStr] = true;
}
function gaOptout() {
	var expDate = new Date;
	expDate.setFullYear(expDate.getFullYear( ) + 10);
	document.cookie = disableStr + '=true; expires=' + expDate.toGMTString( ) + '; path=/';
	window[disableStr] = true;
}
</script>
