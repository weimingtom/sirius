<?php 

date_default_timezone_set('Asia/Chongqing');


/** 
 * 新浪微博操作类 
 * 
 * @package sae 
 * @author Easy Chen 
 * @version 1.0 
 */ 
class QQClient 
{ 
    /** 
     * 构造函数 
     *  
     * @access public 
     * @param mixed $akey 微博开放平台应用APP KEY 
     * @param mixed $skey 微博开放平台应用APP SECRET 
     * @param mixed $accecss_token OAuth认证返回的token 
     * @param mixed $accecss_token_secret OAuth认证返回的token secret 
     * @return void 
     */ 
    function __construct( $akey , $skey , $accecss_token , $accecss_token_secret ) 
    { 
        $this->oauth = new QQOAuth( $akey , $skey , $accecss_token , $accecss_token_secret ); 
    } 

    /** 
     * 最新公共微博 
     *  
     * @access public 
     * @return array 
     */ 
    function public_timeline() 
    { 
        return $this->oauth->get('http://open.t.qq.com/api/statuses/public_timeline'); 
    } 

    /** 
     * 最新关注人微博 
     *  
     * @access public 
     * @return array 
     */ 
    function friends_timeline() 
    { 
        return $this->home_timeline(); 
    } 

    /** 
     * 最新关注人微博 
     *  
     * @access public 
     * @return array 
     */ 
    function home_timeline($count = NULL, $pageflag = NULL, $pagetime = NULL) 
    {
    	$params = array();
    	if ($count != NULL) $params['reqnum'] = $count;
		if ($pageflag != NULL) $params['pageflag'] = $pageflag;
		if ($pagetime != NULL) $params['pagetime'] = $pagetime;
        return $this->oauth->get('http://open.t.qq.com/api/statuses/home_timeline', $params); 
    } 

    /** 
     * 最新 @用户的 
     *  
     * @access public 
     * @param int $page 返回结果的页序号。 
     * @param int $count 每次返回的最大记录数（即页面大小），不大于200，默认为20。 
     * @return array 
     */ 
    function mentions($count = NULL, $pageflag = NULL, $pagetime = NULL) 
    { 
    	$params = array();
    	if ($count != NULL) $params['reqnum'] = $count;
		if ($pageflag != NULL) $params['pageflag'] = $pageflag;
		if ($pagetime != NULL) $params['pagetime'] = $pagetime;
        return $this->oauth->get('http://open.t.qq.com/api/statuses/mentions_timeline', $params); 
    } 


    /** 
     * 发表微博 
     *  
     * @access public 
     * @param mixed $text 要更新的微博信息。 
     * @return array 
     */ 
    function update( $text ) 
    { 
        //  http://open.t.qq.com/api/statuses/update.json 
        $param = array(); 
        $param['content'] = $text; 
        $param['Format'] = 'json';


        return $this->oauth->post( 'http://open.t.qq.com/api/t/add' , $param ); 
    }
    
    /** 
     * 发表图片微博 
     *  
     * @access public 
     * @param string $text 要更新的微博信息。 
     * @param string $text 要发布的图片路径,支持url。[只支持png/jpg/gif三种格式,增加格式请修改get_image_mime方法] 
     * @return array 
     */ 
    function upload( $text , $pic_path ) 
    { 
        //  http://open.t.qq.com/api/statuses/update.json 
        $param = array(); 
        $param['status'] = $text; 
        $param['pic'] = '@'.$pic_path;
        
        return $this->oauth->post( 'http://open.t.qq.com/api/statuses/upload.json' , $param , true ); 
    } 

    /** 
     * 获取单条微博 
     *  
     * @access public 
     * @param mixed $sid 要获取已发表的微博ID 
     * @return array 
     */ 
    function show_status( $sid ) 
    { 
        return $this->oauth->get( 'http://open.t.qq.com/api/t/show', array("id" => $sid) ); 
    } 

    /** 
     * 删除微博 
     *  
     * @access public 
     * @param mixed $sid 要删除的微博ID 
     * @return array 
     */ 
    function delete( $sid ) 
    { 
        return $this->destroy( $sid ); 
    } 

    /** 
     * 删除微博 
     *  
     * @access public 
     * @param mixed $sid 要删除的微博ID 
     * @return array 
     */ 
    function destroy( $sid ) 
    { 
        return $this->oauth->post( 'http://open.t.qq.com/api/statuses/destroy/' . $sid . '.json' ); 
    } 

    /** 
     * 个人资料 
     *  
     * @access public 
     * @param mixed $uid_or_name 用户UID或微博昵称。 
     * @return array 
     */ 
    function show_user($name = null) 
    {
    	if ($name == null) 
        	return $this->oauth->get( 'http://open.t.qq.com/api/user/info');
		else
			return $this->oauth->get( 'http://open.t.qq.com/api/user/other_info', array('name' => $name));
    } 

    /** 
     * 关注人列表 
     *  
     * @access public 
     * @param bool $cursor 单页只能包含100个关注列表，为了获取更多则cursor默认从-1开始，通过增加或减少cursor来获取更多的关注列表 
     * @param bool $count 每次返回的最大记录数（即页面大小），不大于200,默认返回20 
     * @param mixed $uid_or_name 要获取的 UID或微博昵称 
     * @return array 
     */ 
    function friends( $cursor = false , $count = false , $uid_or_name = null ) 
    { 
        return $this->request_with_uid( 'http://open.t.qq.com/api/statuses/friends.json' ,  $uid_or_name , false , $count , $cursor ); 
    } 

    /** 
     * 粉丝列表 
     *  
     * @access public 
     * @param bool $cursor 单页只能包含100个粉丝列表，为了获取更多则cursor默认从-1开始，通过增加或减少cursor来获取更多的粉丝列表 
     * @param bool $count 每次返回的最大记录数（即页面大小），不大于200,默认返回20。 
     * @param mixed $uid_or_name  要获取的 UID或微博昵称 
     * @return array 
     */ 
    function followers( $cursor = false , $count = false , $uid_or_name = null ) 
    { 
        return $this->request_with_uid( 'http://open.t.qq.com/api/statuses/followers.json' ,  $uid_or_name , false , $count , $cursor ); 
    } 

    /** 
     * 关注一个用户 
     *  
     * @access public 
     * @param mixed $uid_or_name 要关注的用户UID或微博昵称 
     * @return array 
     */ 
    function follow( $uid_or_name ) 
    { 
        return $this->request_with_uid( 'http://open.t.qq.com/api/friendships/create.json' ,  $uid_or_name ,  false , false , false , true  ); 
    } 

    /** 
     * 取消关注某用户 
     *  
     * @access public 
     * @param mixed $uid_or_name 要取消关注的用户UID或微博昵称 
     * @return array 
     */ 
    function unfollow( $uid_or_name ) 
    { 
        return $this->request_with_uid( 'http://open.t.qq.com/api/friendships/destroy.json' ,  $uid_or_name ,  false , false , false , true); 
    } 

    /** 
     * 返回两个用户关系的详细情况 
     *  
     * @access public 
     * @param mixed $uid_or_name 要判断的用户UID 
     * @return array 
     */ 
    function is_followed( $uid_or_name ) 
    { 
        $param = array(); 
        if( is_numeric( $uid_or_name ) ) $param['target_id'] = $uid_or_name; 
        else $param['target_screen_name'] = $uid_or_name; 

        return $this->oauth->get( 'http://open.t.qq.com/api/friendships/show.json' , $param ); 
    } 

    /** 
     * 用户发表微博列表 
     *  
     * @access public 
     * @param int $page 页码 
     * @param int $count 每次返回的最大记录数，最多返回200条，默认20。 
     * @param mixed $uid_or_name 指定用户UID或微博昵称 
     * @return array 
     */ 
    function user_timeline($count = NULL, $pageflag = NULL, $pagetime = NULL, $username = NULL) 
    {
    	$params = array();
		if ($username != NULL) $params['name'] = $username;
    	if ($count != NULL) $params['reqnum'] = $count;
		if ($pageflag != NULL) $params['pageflag'] = $pageflag;
		if ($pagetime != NULL) $params['pagetime'] = $pagetime;
        return $this->oauth->get('http://open.t.qq.com/api/statuses/user_timeline', $params); 
    } 

    /** 
     * 获取私信列表 
     *  
     * @access public 
     * @param int $page 页码 
     * @param int $count 每次返回的最大记录数，最多返回200条，默认20。 
     * @return array 
     */ 
    function list_dm( $page = 1 , $count = 20  ) 
    { 
        //return $this->request_with_pager( 'http://open.t.qq.com/api/private/recv' , $page , $count ); 
		return $this->oauth->get( 'http://open.t.qq.com/api/private/recv', array("format"=>"json", "pageflag"=>"0", "pagetime"=>"0", "reqnum"=>"20")); 
    } 

    /** 
     * 发送的私信列表 
     *  
     * @access public 
     * @param int $page 页码 
     * @param int $count 每次返回的最大记录数，最多返回200条，默认20。 
     * @return array 
     */ 
    function list_dm_sent( $page = 1 , $count = 20 ) 
    { 
        return $this->request_with_pager( 'http://open.t.qq.com/api/direct_messages/sent.json' , $page , $count ); 
    } 

    /** 
     * 发送私信 
     *  
     * @access public 
     * @param mixed $uid_or_name UID或微博昵称 
     * @param mixed $text 要发生的消息内容，文本大小必须小于300个汉字。 
     * @return array 
     */ 
    function send_dm( $uid_or_name , $text ) 
    { 
        $param = array(); 
        $param['text'] = $text; 

        if( is_numeric( $uid_or_name ) ) $param['user_id'] = $uid_or_name; 
        else $param['screen_name'] = $uid_or_name; 

        return $this->oauth->post( 'http://open.t.qq.com/api/direct_messages/new.json' , $param  ); 
    } 

    /** 
     * 删除一条私信 
     *  
     * @access public 
     * @param mixed $did 要删除的私信主键ID 
     * @return array 
     */ 
    function delete_dm( $did ) 
    { 
        return $this->oauth->post( 'http://open.t.qq.com/api/direct_messages/destroy/' . $did . '.json' ); 
    } 

    /** 
     * 转发一条微博信息。 
     *  
     * @access public 
     * @param mixed $sid 转发的微博ID 
     * @param bool $text 添加的转发信息。 
     * @return array 
     */ 
    function repost( $sid , $text = false ) 
    { 
        $param = array(); 
        $param['reid'] = $sid; 
        if( $text ) $param['content'] = $text; 

        return $this->oauth->post( 'http://open.t.qq.com/api/t/re_add' , $param  ); 
    } 

    /** 
     * 对一条微博信息进行评论 
     *  
     * @access public 
     * @param mixed $sid 要评论的微博id 
     * @param mixed $text 评论内容 
     * @param bool $cid 要评论的评论id 
     * @return array 
     */ 
    function send_comment( $sid , $text) 
    { 
        $param = array(); 
        $param['reid'] = $sid; 
        $param['content'] = $text; 

        return $this->oauth->post( 'http://open.t.qq.com/api/t/reply' , $param  ); 

    } 

    /** 
     * 发出的评论 
     *  
     * @access public 
     * @param int $page 页码 
     * @param int $count 每次返回的最大记录数，最多返回200条，默认20。 
     * @return array 
     */ 
    function comments_by_me( $page = 1 , $count = 20 ) 
    { 
        return $this->request_with_pager( 'http://open.t.qq.com/api/statuses/comments_by_me.json' , $page , $count ); 
    } 

    /** 
     * 最新评论(按时间) 
     *  
     * @access public 
     * @param int $page 页码 
     * @param int $count 每次返回的最大记录数，最多返回200条，默认20。 
     * @return array 
     */ 
    function comments_timeline( $page = 1 , $count = 20 ) 
    { 
        return $this->request_with_pager( 'http://open.t.qq.com/api/statuses/comments_timeline.json' , $page , $count ); 
    } 

    /** 
     * 单条评论列表(按微博) 
     *  
     * @access public 
     * @param mixed $sid 指定的微博ID 
     * @param int $page 页码 
     * @param int $count 每次返回的最大记录数，最多返回200条，默认20。 
     * @return array 
     */ 
    function get_comments_by_sid( $sid , $page = 1 , $count = 20 ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 
        if( $page ) $param['page'] = $page; 
        if( $count ) $param['count'] = $count; 

        return $this->oauth->get('http://open.t.qq.com/api/statuses/comments.json' , $param ); 

    } 
	
	
	function get_reposts_by_sid( $count = NULL , $pageflag = NULL , $pagetime = NULL, $sid ) 
    { 
        $param = array(); 
        $param['rootid'] = $sid; 
        if( $pageflag ) $param['pageflag'] = $pageflag; 
        if( $pagetime ) $param['pagetime'] = $pagetime; 

        return $this->oauth->get('http://open.t.qq.com/api/t/re_list' , $param ); 

    }

    /** 
     * 批量统计微博的评论数，转发数，一次请求最多获取100个。 
     *  
     * @access public 
     * @param mixed $sids 微博ID号列表，用逗号隔开 
     * @return array 
     */ 
    function get_count_info_by_ids( $sids ) 
    { 
        $param = array(); 
        $param['ids'] = $sids; 

        return $this->oauth->get( 'http://open.t.qq.com/api/statuses/counts.json' , $param ); 
    } 

    /** 
     * 对一条微博评论信息进行回复。 
     *  
     * @access public 
     * @param mixed $sid 微博id 
     * @param mixed $text 评论内容。 
     * @param mixed $cid 评论id 
     * @return array 
     */ 
    function reply( $sid , $text , $cid ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 
        $param['comment'] = $text; 
        $param['cid '] = $cid; 

        return $this->oauth->post( 'http://open.t.qq.com/api/statuses/reply.json' , $param  ); 

    } 

    /** 
     * 返回用户的发布的最近20条收藏信息，和用户收藏页面返回内容是一致的。 
     *  
     * @access public 
     * @param bool $page 返回结果的页序号。 
     * @return array 
     */ 
    function get_favorites( $page = false ) 
    { 
        $param = array(); 
        if( $page ) $param['page'] = $page; 

        return $this->oauth->get( 'http://open.t.qq.com/api/favorites.json' , $param ); 
    } 

    /** 
     * 收藏一条微博信息 
     *  
     * @access public 
     * @param mixed $sid 收藏的微博id 
     * @return array 
     */ 
    function add_to_favorites( $sid ) 
    { 
        $param = array(); 
        $param['id'] = $sid; 

        return $this->oauth->post( 'http://open.t.qq.com/api/favorites/create.json' , $param ); 
    } 

    /** 
     * 删除微博收藏。 
     *  
     * @access public 
     * @param mixed $sid 要删除的收藏微博信息ID. 
     * @return array 
     */ 
    function remove_from_favorites( $sid ) 
    { 
        return $this->oauth->post( 'http://open.t.qq.com/api/favorites/destroy/' . $sid . '.json'  ); 
    } 
    
    
    function verify_credentials() 
    { 
        return $this->oauth->get( 'http://open.t.qq.com/api/account/verify_credentials.json' );
    }
    
    function update_avatar( $pic_path )
	{
		$param = array();
		$param['image'] = "@".$pic_path;
        
        return $this->oauth->post( 'http://open.t.qq.com/api/account/update_profile_image.json' , $param , true ); 
	
	} 

    function trend_timeline($count = NULL, $pageflag = NULL, $pagetime = NULL, $trend_name = NULL) 
    {
    	if ($trend_name == NULL) return array();
		 
    	$params = array();
		$params['httext'] = $trend_name;
    	if ($count != NULL) $params['reqnum'] = $count;
		if ($pageflag != NULL) $params['pageflag'] = $pageflag;
		if ($pagetime != NULL) $params['pagetime'] = $pagetime;
        return $this->oauth->get('http://open.t.qq.com/api/statuses/ht_timeline', $params); 
    } 

    // ========================================= 

    /** 
     * @ignore 
     */ 
    protected function request_with_pager( $url , $page = false , $count = false ) 
    { 
        $param = array(); 
        if( $page ) $param['page'] = $page; 
        if( $count ) $param['count'] = $count; 

        return $this->oauth->get($url , $param ); 
    } 

    /** 
     * @ignore 
     */ 
    protected function request_with_uid( $url , $uid_or_name , $page = false , $count = false , $cursor = false , $post = false ) 
    { 
        $param = array(); 
        if( $page ) $param['page'] = $page; 
        if( $count ) $param['reqnum'] = $count; 
        if( $cursor )$param['cursor'] =  $cursor; 

        if( $post ) $method = 'post'; 
        else $method = 'get'; 

  		if( $uid_or_name !== null ) 
        { 
            $param['name'] = $uid_or_name; 
            return $this->oauth->$method($url , $param ); 
        } 
        else 
        { 
            return $this->oauth->$method($url , $param ); 
        } 

    } 

} 

/** 
 * 新浪微博 OAuth 认证类 
 * 
 * @package sae 
 * @author Easy Chen 
 * @version 1.0 
 */ 
class QQOAuth { 
    /** 
     * Contains the last HTTP status code returned.  
     * 
     * @ignore 
     */ 
    public $http_code; 
    /** 
     * Contains the last API call. 
     * 
     * @ignore 
     */ 
    public $url; 
    /** 
     * Set up the API root URL. 
     * 
     * @ignore 
     */ 
    public $host = "https://open.t.qq.com/"; 
    /** 
     * Set timeout default. 
     * 
     * @ignore 
     */ 
    public $timeout = 30; 
    /**  
     * Set connect timeout. 
     * 
     * @ignore 
     */ 
    public $connecttimeout = 30;  
    /** 
     * Verify SSL Cert. 
     * 
     * @ignore 
     */ 
    public $ssl_verifypeer = FALSE; 
    /** 
     * Respons format. 
     * 
     * @ignore 
     */ 
    public $format = 'json'; 
    /** 
     * Decode returned json data. 
     * 
     * @ignore 
     */ 
    public $decode_json = TRUE; 
    /** 
     * Contains the last HTTP headers returned. 
     * 
     * @ignore 
     */ 
    public $http_info; 
    /** 
     * Set the useragnet. 
     * 
     * @ignore 
     */ 
    public $useragent = 'Sirius Server v0.1'; 
    /* Immediately retry the API call if the response was not successful. */ 
    //public $retry = TRUE; 
    



    /** 
     * Set API URLS 
     */ 
    /** 
     * @ignore 
     */ 
    function accessTokenURL()  { return 'https://open.t.qq.com/cgi-bin/access_token'; } 
    /** 
     * @ignore 
     */ 
    function authenticateURL() { return 'https://open.t.qq.com/cgi-bin/authenticate'; } 
    /** 
     * @ignore 
     */ 
    function authorizeURL()    { return 'https://open.t.qq.com/cgi-bin/authorize'; } 
    /** 
     * @ignore 
     */ 
    function requestTokenURL() { return 'https://open.t.qq.com/cgi-bin/request_token'; } 


    /** 
     * Debug helpers 
     */ 
    /** 
     * @ignore 
     */ 
    function lastStatusCode() { return $this->http_status; } 
    /** 
     * @ignore 
     */ 
    function lastAPICall() { return $this->last_api_call; } 

    /** 
     * construct WeiboOAuth object 
     */ 
    function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) { 
        $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1(); 
        $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret); 
        if (!empty($oauth_token) && !empty($oauth_token_secret)) { 
            $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret); 
        } else { 
            $this->token = NULL; 
        } 
    } 


    /** 
     * Get a request_token from Weibo 
     * 
     * @return array a key/value array containing oauth_token and oauth_token_secret 
     */ 
    function getRequestToken($oauth_callback = NULL) { 
        $parameters = array(); 
        if (!empty($oauth_callback)) { 
            $parameters['oauth_callback'] = $oauth_callback; 
        }  

        $request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters); 
        $token = OAuthUtil::parse_parameters($request); 
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']); 
        return $token; 
    } 

    /** 
     * Get the authorize URL 
     * 
     * @return string 
     */ 
    function getAuthorizeURL($token, $sign_in_with_Weibo = TRUE , $url) { 
        if (is_array($token)) { 
            $token = $token['oauth_token']; 
        } 
        if (empty($sign_in_with_Weibo)) { 
            return $this->authorizeURL() . "?oauth_token={$token}&oauth_callback=" . urlencode($url); 
        } else { 
            return $this->authenticateURL() . "?oauth_token={$token}&oauth_callback=". urlencode($url); 
        } 
    } 

    /** 
     * Exchange the request token and secret for an access token and 
     * secret, to sign API calls. 
     * 
     * @return array array("oauth_token" => the access token, 
     *                "oauth_token_secret" => the access secret) 
     */ 
    function getAccessToken($oauth_verifier = FALSE, $oauth_token = false) { 
        $parameters = array(); 
        if (!empty($oauth_verifier)) { 
            $parameters['oauth_verifier'] = $oauth_verifier; 
        } 


        $request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters); 
        $token = OAuthUtil::parse_parameters($request); 
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']); 
        return $token; 
    } 

    /** 
     * GET wrappwer for oAuthRequest. 
     * 
     * @return mixed 
     */ 
    function get($url, $parameters = array()) { 
        $response = $this->oAuthRequest($url, 'GET', $parameters); 
        if ($this->format === 'json' && $this->decode_json) { 
            return json_decode($response, true); 
        } 
        return $response; 
    } 

    /** 
     * POST wreapper for oAuthRequest. 
     * 
     * @return mixed 
     */ 
    function post($url, $parameters = array() , $multi = false) { 
        
        $response = $this->oAuthRequest($url, 'POST', $parameters , $multi ); 
        if ($this->format === 'json' && $this->decode_json) { 
            return json_decode($response, true); 
        } 
        return $response; 
    } 

    /** 
     * DELTE wrapper for oAuthReqeust. 
     * 
     * @return mixed 
     */ 
    function delete($url, $parameters = array()) { 
        $response = $this->oAuthRequest($url, 'DELETE', $parameters); 
        if ($this->format === 'json' && $this->decode_json) { 
            return json_decode($response, true); 
        } 
        return $response; 
    } 

    /** 
     * Format and sign an OAuth / API request 
     * 
     * @return string 
     */ 
    function oAuthRequest($url, $method, $parameters , $multi = false) { 

        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) { 
            $url = "{$this->host}{$url}.{$this->format}"; 
        } 

        // echo $url ; 
        $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters); 
        $request->sign_request($this->sha1_method, $this->consumer, $this->token); 
        switch ($method) { 
        case 'GET': 
            //echo $request->to_url(); 
            return $this->http($request->to_url(), 'GET'); 
        default: 
            return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata($multi) , $multi ); 
        } 
    } 

    /** 
     * Make an HTTP request 
     * 
     * @return string API results 
     */ 
    function http($url, $method, $postfields = NULL , $multi = false) { 
        $this->http_info = array(); 
        $ci = curl_init(); 
        /* Curl settings */ 
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

        $header_array = array(); 
        
/*
        $header_array["FetchUrl"] = $url; 
        $header_array['TimeStamp'] = date('Y-m-d H:i:s'); 
        $header_array['AccessKey'] = SAE_ACCESSKEY; 


        $content="FetchUrl"; 

        $content.=$header_array["FetchUrl"]; 

        $content.="TimeStamp"; 

        $content.=$header_array['TimeStamp']; 

        $content.="AccessKey"; 

        $content.=$header_array['AccessKey']; 

        $header_array['Signature'] = base64_encode(hash_hmac('sha256',$content, SAE_SECRETKEY ,true)); 
*/
        //curl_setopt($ci, CURLOPT_URL, SAE_FETCHURL_SERVICE_ADDRESS ); 

        //print_r( $header_array ); 
        $header_array2=array(); 
        if( $multi ) 
        	$header_array2 = array("Content-Type: multipart/form-data; boundary=" . OAuthUtil::$boundary , "Expect: ");
        foreach($header_array as $k => $v) 
            array_push($header_array2,$k.': '.$v); 

        curl_setopt($ci, CURLOPT_HTTPHEADER, $header_array2 ); 
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE ); 

        //echo $url."<hr/>"; 

        curl_setopt($ci, CURLOPT_URL, $url); 

        $response = curl_exec($ci); 
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE); 
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci)); 
        $this->url = $url; 

        //echo '=====info====='."\r\n";
        //print_r( curl_getinfo($ci) ); 
        
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

