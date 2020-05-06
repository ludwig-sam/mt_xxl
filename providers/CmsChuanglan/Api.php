<?php namespace Providers\CmsChuanglan;

class Api {

    private $account;
    private $password;
    private $api_send_url;
    private $api_var_url;
    private $api_balance_query_url;

    public $reqFields;


    public function __construct($config)
    {
        $this->account                  = $config['account'];
        $this->password                 = $config['password'];
        $this->api_send_url             = $config['host'] . '/msg/send/json';
        $this->api_var_url              = $config['host'] . '/msg/variable/json';
        $this->api_balance_query_url    = $config['host'] . '/msg/balance/json';
    }

	public function sendSMS( $phone, $msg, $report = 'true') {
        $account  = $this->account;
        $password = $this->password;
        $msg      = urlencode($msg);
		return $this->curlPost( $this->api_send_url , compact('account', 'password', 'msg', 'phone', 'report'));
	}
	
	public function sendVariableSMS( $msg, $params) {
        $account  = $this->account;
        $password = $this->password;
        $msg      = urlencode($msg);
        $report   = true;
        return $this->curlPost( $this->api_send_url , compact('account', 'password', 'msg', 'params', 'report'));
	}
	
	public function queryBalance() {
        $account  = $this->account;
        $password = $this->password;
		return $this->curlPost($this->api_balance_query_url, compact('account', 'password'));
	}

	private function curlPost($url,$postFields){
		$postFields = json_encode($postFields);
		$ch = curl_init ();
		curl_setopt( $ch, CURLOPT_URL, $url ); 
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charset=utf-8'
			)
		);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt( $ch, CURLOPT_TIMEOUT,3);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
		$ret = curl_exec ( $ch );
        if (false == $ret) {
            $result = '{"code":"500", "curl_error" : "' . curl_error(  $ch) . '"}';
        } else {
            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = '{"code":"500","errorMsg":' . "请求状态 ". $rsp . ' ' . curl_error($ch). '}';
            } else {
                $result  = $ret;
            }
        }
		curl_close ( $ch );

        $this->reqFields = [
            'url'     => $url,
            'post'    => $postFields
        ];

		return $result;
	}
	
}