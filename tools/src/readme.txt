/**
 * Copyright 2013 Alin Marcu
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
 
 * Custom GACWP EndPoint:

 		- added an action hook to IO -> Abstract -> MakeRequest to enable custom endpoint support:
 
			   public function makeRequest(Deconfc_Http_Request $request)
			   {
			
				  	// Add support for GACWP Endpoint
				  	do_action('gacwp_endpoint_support', $request);
				  	
				  	...
				 
				 }
				 
 * Updated the IO -> cacerts.pem file to support Let's Encrypt certificates
 
 * Changed 'Google' provider to 'Deconfc', to avoid conflicts on different versions of GAPI PHP Client