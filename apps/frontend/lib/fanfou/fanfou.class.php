<?php

class fanfouClient {
	private $username = "";
	private $password = "";
	private $format = "json";
	private $decode_json = true;
	private $ssl_verifypeer = FALSE;
	private $useragent = 'Sirius Server v0.1';
	private $timeout = 30;
	private $connecttimeout = 30;
	public $http_code; 
	public $http_info;
	
	
	function __construct( $username , $password ) {
		$this->username = $username;
		$this->password = $password;
	}
	
	function getUserInfo($userId = null) {
		$params = array();
		if ($userId) $params['id'] = $userId;
		return $this->get("http://api.fanfou.com/users/show.json", $params);
	}
	
	function friendsTimeline($since_id = null, $page = 1, $count = 20, $max_id = null) {
		$params = array('format'=>'html');
		if( $since_id ) $params['since_id'] = $since_id; 
		if( $page ) $params['page'] = $page; 
   		if( $count ) $params['count'] = $count; 			
    	if( $max_id ) $params['max_id'] = $max_id;
    	
		return $this->get("http://api.fanfou.com/statuses/friends_timeline.json", $params);
	}
		
	function userTimeline($since_id = null, $page = 1, $count = 20, $max_id = null, $userId = null) {
		$params = array('format'=>'html');
		if( $since_id ) $params['since_id'] = $since_id; 
		if( $page ) $params['page'] = $page; 
   		if( $count ) $params['count'] = $count; 			
    	if( $max_id ) $params['max_id'] = $max_id; 
    	if( $userId ) $params['id'] = $userId;
		
		return $this->get("http://api.fanfou.com/statuses/user_timeline.json", $params);
	}
	
	
	function get($url, $parameters = array()) {//echo $this->toUrl($url, $parameters); die();
        $response = $this->http($this->toUrl($url, $parameters), 'GET');
        if ($this->format === 'json' && $this->decode_json) { 
            return json_decode($response, true); 
        } 
        return $response; 
    } 
    
    public function toUrl($url, $params) {
    	foreach ($params as $k => $p) {
    		$out .= '&' . urlencode($k) . '=' . urlencode($p);
    	} 
        if ($out) { 
            $url .= '?' . substr($out, 1); 
        } 
        return $url; 
    } 
	
	function http($url, $method, $postfields = NULL , $multi = false) {
		$this->http_info = array(); 
        $ci = curl_init(); 
        /* Curl settings */ 
        if ($this->username && $this->password) {
        	curl_setopt($ci, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
			curl_setopt($ci, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        }

        curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent); 
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout); 
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout); 
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE); 
        
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer); 

        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));         

        curl_setopt($ci, CURLOPT_HEADER, FALSE); 
        
        switch ($method) { 
        case 'POST': 
            curl_setopt($ci, CURLOPT_POST, TRUE); 
            if (!empty($postfields)) { 
                curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields); 
                //echo "=====post data======\r\n";
                //echo $postfields;
            } 
            break; 
        case 'DELETE': 
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE'); 
            if (!empty($postfields)) { 
                $url = "{$url}?{$postfields}"; 
            } 
        } 

/*        $header_array2=array(); 
        if( $multi ) 
        	$header_array2 = array("Content-Type: multipart/form-data; boundary=" . OAuthUtil::$boundary , "Expect: ");
        foreach($header_array2 as $k => $v) 
            array_push($header_array2,$k.': '.$v); 

        curl_setopt($ci, CURLOPT_HTTPHEADER, $header_array2 ); 
*/
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE ); 

        curl_setopt($ci, CURLOPT_URL, $url); 

        $response = curl_exec($ci); 
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE); 
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci)); 
        $this->url = $url; 
        //echo '=====info====='."\r\n";
        //print_r( curl_getinfo($ci) );
        //print_r($this->http_code);
        //print_r($this->http_info); 
        
        //echo '=====$response====='."\r\n";
        //print_r( $response ); 

        
        curl_close ($ci); 
        return $response; 
	}
	/** 
     * Get the header info to store. 
     * 
     * @return int 
     */ 
    function getHeader($ch, $header) { 
        $i = strpos($header, ':'); 
        if (!empty($i)) { 
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i))); 
            $value = trim(substr($header, $i + 2)); 
            $this->http_header[$key] = $value; 
        } 
        return strlen($header); 
    } 
}