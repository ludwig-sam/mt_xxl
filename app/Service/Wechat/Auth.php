<?php namespace App\Service\Wechat;



use Libs\Route;

class Auth  extends Wechat {


    private function baseMook()
    {
        return [
            'id' => 'oAWKk0aoJrGwDMvdfmR-rb6mWeIY'
        ];
    }

    private function app($callback_params = [])
    {
        return $this->serve([
            'oauth' => [
                'scopes'   => ['snsapi_base'],
                'callback' => Route::named('wx_auth_callback', $callback_params),
            ],
        ])->oauth;
    }

    public function base($target, $close)
    {
        if(isDev()){
            return $close($this->baseMook());
        }

        $wechat_user = session('wechat_user');

        if (empty($wechat_user)) {

            session([
                'target_url' => $target
            ]);

            return $this->app([
                'target_url' => $target
            ])->redirect();
        }

        return $close($wechat_user);
    }

    public function user()
    {
        return $this->serve()->oauth->user();
    }

    public function callback()
    {
        session([
           'wechat_user' =>  $this->user()->toArray()
        ]);

        return session('target_url') ? : request()->get('target_url');
    }
}