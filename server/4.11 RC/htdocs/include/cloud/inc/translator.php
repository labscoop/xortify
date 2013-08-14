<?php
/**
 * Xortify API Function
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Coopertive (Australia)  http://web.labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         api
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */
	 
	 /**
	 * Google Translator v2.0 API Class
	 *
	 * Allows for Translation of Language by the Google Translator API
	 *
	 * @subpackage google
	 */
    class GooglosityTranslator
	{
		// this is the API endpoint, as specified by Google
		const ENDPOINT = 'https://www.googleapis.com/language/translate/v2';
 
		// holder for you API key, specified when an instance is created
		// Get From: https://code.google.com/apis/console/
		protected $_apiKey;
 
		/**
		 * __construct() 
		 * Class constructor
		 * 
		 * @param string $apiKey		Google API V2 Key (see: https://code.google.com/apis/console/)
		 */
		public function __construct($apiKey)
		{
			$this->_apiKey = (!empty($apiKey)?$apiKey:_MI_SERVER_GOOGLE_TRANSLATOR_KEY);
		}
		
		/**
		 * _languages()
		 * returns language ISO Codes and XOOPS folder name for Google Translator
		 *
		 * @return array
		 */
		static public function _languages() {
			return array("af" => "Afrikaans", "ak" => "Akan", "sq" => "Albanian", "am" => "Amharic", "ar" => "Arabic", "hy" => "Armenian", "az" => "Azerbaijani", "eu" => "Basque", "be" => "Belarusian", "bem" => "Bemba", "bn" => "Bengali", "bh" => "Bihari", "xx-bork" => "Bork, bork, bork!", "bs" => "Bosnian", "br" => "Breton", "bg" => "Bulgarian", "km" => "Cambodian", "ca" => "Catalan", "chr" => "Cherokee", "ny" => "Chichewa", "zh-CN" => "Chinese", "zh-TW" => "Chinese Traditional", "co" => "Corsican", "hr" => "Croatian", "cs" => "Czech", "da" => "Danish", "nl" => "Dutch", "xx-elmer" => "Elmer Fudd", "en" => "English", "eo" => "Esperanto", "et" => "Estonian", "ee" => "Ewe", "fo" => "Faroese", "tl" => "Filipino", "fi" => "Finnish", "fr" => "French", "fy" => "Frisian", "gaa" => "Ga", "gl" => "Galician", "ka" => "Georgian", "de" => "German", "el" => "Greek", "gn" => "Guarani", "gu" => "Gujarati", "xx-hacker" => "Hacker", "ht" => "Haitian Creole", "ha" => "Hausa", "haw" => "Hawaiian", "iw" => "Hebrew", "hi" => "Hindi", "hu" => "Hungarian", "is" => "Icelandic", "ig" => "Igbo", "id" => "Indonesian", "ia" => "Interlingua", "ga" => "Irish", "it" => "Italian", "ja" => "Japanese", "jw" => "Javanese", "kn" => "Kannada", "kk" => "Kazakh", "rw" => "Kinyarwanda", "rn" => "Kirundi", "xx-klingon" => "Klingon", "kg" => "Kongo", "ko" => "Korean", "kri" => "Sierra Leone", "ku" => "Kurdish", "ckb" => "Sorani", "ky" => "Kyrgyz", "lo" => "Laothian", "la" => "Latin", "lv" => "Latvian", "ln" => "Lingala", "lt" => "Lithuanian", "loz" => "Lozi", "lg" => "Luganda", "ach" => "Luo", "mk" => "Macedonian", "mg" => "Malagasy", "ms" => "Malay", "ml" => "Malayalam", "mt" => "Maltese", "mi" => "Maori", "mr" => "Marathi", "mfe" => "Mauritian Creole", "mo" => "Moldavian", "mn" => "Mongolian", "sr-ME" => "Montenegrin", "ne" => "Nepali", "pcm" => "Nigerian Pidgin", "nso" => "Northern Sotho", "no" => "Norwegian", "nn" => "Nynorsk", "oc" => "Occitan", "or" => "Oriya", "om" => "Oromo", "ps" => "Pashto", "fa" => "Persian", "xx-pirate" => "Pirate", "pl" => "Polish", "pt-BR" => "Brazillian", "pt-PT" => "Portugal", "pa" => "Punjabi", "qu" => "Quechua", "ro" => "Romanian", "rm" => "Romansh", "nyn" => "Runyakitara", "ru" => "Russian", "gd" => "Scots Gaelic", "sr" => "Serbian", "sh" => "Serbo-Croatian", "st" => "Sesotho", "tn" => "Setswana", "crs" => "Seychellois Creole", "sn" => "Shona", "sd" => "Sindhi", "si" => "Sinhalese", "sk" => "Slovak", "sl" => "Slovenian", "so" => "Somali", "es" => "Spanish", "es-419" => "Latin American", "su" => "Sundanese", "sw" => "Swahili", "sv" => "Swedish", "tg" => "Tajik", "ta" => "Tamil", "tt" => "Tatar", "te" => "Telugu", "th" => "Thai", "ti" => "Tigrinya", "to" => "Tonga", "lua" => "Tshiluba", "tum" => "Tumbuka", "tr" => "Turkish", "tk" => "Turkmen", "tw" => "Twi", "ug" => "Uighur", "uk" => "Ukrainian", "ur" => "Urdu", "uz" => "Uzbek", "vi" => "Vietnamese", "cy" => "Welsh", "wo" => "Wolof", "xh" => "Xhosa", "yi" => "Yiddish", "yo" => "Yoruba", "zu" => "Zulu");
		}
 
 		/**
		 * getLanguages() 
		 * returns language ISO Codes for Google Translator
		 * 
		 * @return array
		 */
		public function getLanguages()
		{
			return array("af", "ach", "ak", "am", "ar", "az", "be", "bem", "bg", "bh", "bn", "br", "bs", "ca", "chr", "ckb", " co", "crs", "cs", "cy", "da", "de", "ee", "el", "en", "eo", "es", "es-419", "et", "eu", "fa", "fi", " fo", "fr", "fy", "ga", "gaa", "gd", "gl", "gn", "gu", "ha", "haw", "hi", "hr", "ht", "hu", "hy", "ia", " id", "ig", "is", "it", "iw", "ja", "jw", "ka", "kg", "kk", "km", "kn", "ko", "kri", "ku", "ky", "la", " lg", "ln", "lo", "loz", "lt", "lua", "lv", "mfe", "mg", "mi", "mk", "ml", "mn", "mo", "mr", "ms", "mt", "ne", "nl", "nn", "no", "nso", "ny", "nyn", "oc", "om", "or", "pa", "pcm", "pl", "ps", "pt-BR", " pt-PT", "qu", "rm", "rn", "ro", "ru", "rw", "sd", "sh", "si", "sk", "sl", "sn", "so", "sq", "sr", " sr-ME", "st", "su", "sv", "sw", "ta", "te", "tg", "th", "ti", "tk", "tl", "tn", "to", "tr", "tt", " tum", "tw", "ug", "uk", "ur", "uz", "vi", "wo", "xh", "xx-bork", "xx-elmer", "xx-hacker", " xx-klingon", "xx-pirate", "yi", "yo", "zh-CN", "zh-TW", "zu");
		}

		/**
		 * translate() 
		 * translate the text/html in $data. Translates to the language in $target. Can optionally specify the source language
		 * 
		 * @param string $src			Data to be translated.
		 * @param string $target		Language ISO Code to translate To.
		 * @param string $source		Language ISO Code to translate From.
		 * @return string
		 */		
		public function translate($src, $target, $source = '')
		{
			// this is the form data to be included with the request
			$values = array(
				'key'    => $this->_apiKey,
				'target' => $target,
				'q'      => $src
			);
 
			// only include the source data if it's been specified
			if (strlen($source) > 0) {
				$values['source'] = $source;
			}
 
			// turn the form data array into raw format so it can be used with cURL
			$formData = http_build_query($values);
 
			$ch = curl_init();
			//Set the Curl url.
			curl_setopt ($ch, CURLOPT_URL, self::ENDPOINT.'?'.$formData);
			//Set the HTTP HEADER Fields.
			curl_setopt ($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: GET'));
			//CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
			//CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
			//Execute the  cURL session.
			$json = curl_exec($ch);
			//Get the Error Code returned by Curl.
			$json_errono = curl_errno($ch);
			// create a connection to the API endpoint
			curl_close($ch);
			// decode the response data
			$data = json_decode($json, true);
		
			// ensure the returned data is valid
			if (!is_array($data) || !array_key_exists('data', $data)) {
				$GLOBAL['translation'] = false;
				return $src;
			}
 
			// ensure the returned data is valid
			if (!array_key_exists('translations', $data['data'])) {
				$GLOBAL['translation'] = false;
				return $src;
			}
 
			if (!is_array($data['data']['translations'])) {
				$GLOBAL['translation'] = false;
				return $src;
			}
 
			// loop over the translations and return the first one.
			// if you wanted to handle multiple translations in a single call
			// you would need to modify how this returns data
			foreach ($data['data']['translations'] as $translation) {
				$GLOBAL['translation'] = true;
				return $translation['translatedText'];
			}
 
			$GLOBAL['translation'] = false;
			return $src;
		}
	}
	
	/**
	 * Microsoft v2.0 API Access Token Authentication Class
	 * Allows for Access Token Authentication string to be retreived
	 *
	 * @subpackage microsoft
	 */	
	class AccessTokenAuthentication {
		
		/* getTokens()
		 * Get the access token.
		 *
		 * @param string $grantType    Grant type.
		 * @param string $scopeUrl     Application Scope URL.
		 * @param string $clientID     Application client ID.
		 * @param string $clientSecret Application client ID.
		 * @param string $authUrl      Oauth Url.
		 *
		 * @return string.
		 */
		function getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl){
			try {
				//Initialize the Curl Session.
				$ch = curl_init();
				//Create the request Array.
				$paramArr = array (
				 'grant_type'    => $grantType,
				 'scope'         => $scopeUrl,
				 'client_id'     => $clientID,
				 'client_secret' => $clientSecret
				);
				//Create an Http Query.//
				$paramArr = http_build_query($paramArr);
				//Set the Curl URL.
				curl_setopt($ch, CURLOPT_URL, $authUrl);
				//Set HTTP POST Request.
				curl_setopt($ch, CURLOPT_POST, TRUE);
				//Set data to POST in HTTP "POST" Operation.
				curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
				//CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
				//CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				//Execute the  cURL session.
				$strResponse = curl_exec($ch);
				//Get the Error Code returned by Curl.
				$curlErrno = curl_errno($ch);
				if($curlErrno){
					$curlError = curl_error($ch);
					throw new Exception($curlError);
				}
				//Close the Curl Session.
				curl_close($ch);
				//Decode the returned JSON string.
				$objResponse = json_decode($strResponse);
				return $objResponse->access_token;
			} catch (Exception $e) {
				echo "Exception-".$e->getMessage();
			}
		}
	}

	/**
	 * Microsoft v2.0 API Bing Translation Class
	 * Allows with a Access Token Authentication string to access and translate language with the microsoft API v2
	 *
	 * @subpackage microsoft
	 */	
	Class BingTranslator
	{		


		/**
		 * _languages()
		 * returns language ISO Codes and XOOPS folder name for Google Translator
		 *
		 * @return array
		 */
		static public function _languages() {
			return array("ar" => "Arabic", "bg" => "Bulgarian", "ca" => "Catalan", "zh-CHS" => "Chinese", "zh-CHT" => "Chinese Traditional", "cs" => "Czech", "da" => "Danish", "nl" => "Dutch", "en" => "English", "et" => "Estonian", "fa" => "Farsi", "fi" => "Finnish", "fr" => "French", "de" => "German", "el" => "Greek", "ht" => "Haitian Creole", "he" => "Hebrew", "hi" => "Hindi", "hu" => "Hungarian", "id" => "Indonesian", "it" => "Italian", "ja" => "Japanese", "ko" => "Korean", "lv" => "Latvian", "lt" => "Lithuanian", "ms" => "Malay", "mww" => "Hmong Daw", "no" => "Norwegian", "pl" => "Polish", "pt" => "Portuguese", "ro" => "Romanian", "ru" => "Russian", "sk" => "Slovak", "sl" => "Slovenian", "es" => "Spanish", "sv" => "Swedish", "th" => "Thai", "tr" => "Turkish", "uk" => "Ukrainian", "ur" => "Urdu", "vi" => "Vietnamese");
		}
		
		/**
		 * getLanguages() 
		 * returns language ISO Codes for Micorsoft Bing Translator
		 * 
		 * @return array
		 */
		function getLanguages() {
			return array("ar", "bg", "ca", "zh-CHS", "zh-CHT", "cs", "da", "nl", "en", "et", "fa", "fi", "fr", "de", "el", "ht", "he", "hi", "hu", "id", "it", "ja", "ko", "lv", "lt", "ms", "mww", "no", "pl", "pt", "ro", "ru", "sk", "sl", "es", "sv", "th", "tr", "uk", "ur", "vi");
		}
		
		/* getXMLDoc()
		 * Create and execute the HTTP CURL request.
		 * 
		 * @param string $url        	HTTP Url.
		 * @param string $authHeader 	Authorization Header string.
		 *
		 * @return string.
		 *
		 */
		private function getXMLDoc($url, $authHeader) {
			//Initialize the Curl Session.
			$ch = curl_init();
			//Set the Curl url.
			curl_setopt ($ch, CURLOPT_URL, $url);
			//Set the HTTP HEADER Fields.
			curl_setopt ($ch, CURLOPT_HTTPHEADER, array($authHeader,"Content-Type: text/xml"));
			//CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
			//CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
			//Execute the  cURL session.
			$curlResponse = curl_exec($ch);
			//Get the Error Code returned by Curl.
			$curlErrno = curl_errno($ch);
			if ($curlErrno) {
				$GLOBAL['translation'] = false;
				return false;
			}
			//Close a cURL session.
			curl_close($ch);
			$GLOBAL['translation'] = true;
			return $curlResponse;
		}

		/* getAuthHeaders()
		 * Create oAuth cURL Headers.
		*
		* @return array.
		*
		*/
		private function getAuthHeaders() {
			xoops_loadLanguage('keys');
			$clientID     	= _MI_SERVER_BING_TRANSLATOR_CLIENTID;
			$clientSecret 	= _MI_SERVER_BING_TRANSLATOR_CLIENTSECRET;
			$authUrl      	= "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
			$scopeUrl     	= "http://api.microsofttranslator.com";
			$grantType    	= "client_credentials";
			$authObj      	= new AccessTokenAuthentication();
			return (string)"Authorization: Bearer ". $authObj->getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl);
		}
		
		/* translate()
		 * translate the text/html in $data. Translates to the language in $target. Can optionally specify the source language.
		 * 
		 * @param string $content    	Data to be translated.
		 * @param string $from 			Language ISO Code to translate From.
		 * @param string $to 			Language ISO Code to translate To.
		 * @param string $headers 		Headers to be Passed by CURL.
		 *
		 * @return string.
		 *
		 */
		public function translate($content, $from = 'en', $to = 'fr')
		{
			$data = array(
				'appId' => '',
				'text'  => $content,
				'from'  => $from,
				'to'    => $to,
			);
			$urlTarget = 'http://api.microsofttranslator.com/v2/Http.svc/Translate?' . http_build_query($data);
			return @$this->getXMLDoc($urlTarget, $this->getAuthHeaders());
		}
	}

?>