<?php

namespace App\Http\Controllers;

use App\Hackathon\User;
use Auth;
use Exception;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function facebookSignIn()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookSignInCallback()
    {
        try
        {
            $fb = Socialite::driver('facebook')->user();
        }
        catch (Exception $e)
        {
            return 'Authenticate Failed.';
        }

        $user = $this->updateOrCreateUser($fb->getId(), $fb->token, [
            'name' => $fb->getName(),
            'email' => $fb->getEmail(),
            'avatar' => $fb->getAvatar(),
            'gender' => $fb->user['gender'],
        ]);

        Auth::loginUsingId($user->id);

        return redirect('https://apps.facebook.com/1645529079022730/');
    }

    public function facebookSignInWithCanvas()
    {
        $fb = new Facebook(config('services.fb'));

        $helper = $fb->getCanvasHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(FacebookResponseException $e) {
            return '伺服器似乎發生了點狀況，請稍候再訪問';
            //return 'Graph returned an error: ' . $e->getMessage();
        } catch(FacebookSDKException $e) {
            return '伺服器似乎發生了點狀況，請稍候再訪問';
            //return 'Facebook SDK returned an error: ' . $e->getMessage();
        }

        if ( ! isset($accessToken)) {
            return '<a href="https://hackathon.bepsvpt.net/auth/facebook" target="_blank">您尚未啟用本應用程式，點擊此連結已完成驗證</a>';
        }

        if (null === ($user = User::find($helper->getSignedRequest()->get('user_id'))))
        {
            $fb = new Facebook(config('services.fb'));

            try {
                $response = $fb->get('/me?fields=id,name,email,gender', $accessToken->getValue());
            } catch(FacebookResponseException $e) {
                return '伺服器似乎發生了點狀況，請稍候再訪問';
                //return 'Graph returned an error: ' . $e->getMessage();
            } catch(FacebookSDKException $e) {
                return '伺服器似乎發生了點狀況，請稍候再訪問';
                //return 'Facebook SDK returned an error: ' . $e->getMessage();
            }

            $graphUser = $response->getGraphUser();

            $user = $this->updateOrCreateUser($graphUser['id'], $accessToken->getValue(), [
                'name' => $graphUser['name'],
                'email' => (isset($graphUser['email'])) ? $graphUser['email'] : null,
                'avatar' => 'https://graph.facebook.com/v2.4/' . $graphUser['id'] . '/picture?type=normal',
                'gender' => $graphUser['gender'],
            ]);
        }
        else
        {
            $user->update(['accessToken' => $accessToken->getValue()]);
        }

        Auth::loginUsingId($user->id);

        return redirect()->route('facebook.pages');
    }

    public function updateOrCreateUser($id, $accessToken, array $data)
    {
        return User::updateOrCreate(['id' => $id], [
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'avatar' => $data['avatar'],
            'gender' => $data['gender'],
            'accessToken' => $accessToken,
        ]);
    }
}