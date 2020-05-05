<?php

namespace App\Traits;

use App\Models\AuthProvider;
use App\User;
use TwitchApi;

trait Twitch
{

    public static function getAuthenticationUrl($state = null, $forceVerify = false)
    {
        return TwitchApi::getAuthenticationUrl($state, $forceVerify);
    }

    public static function authenticate($code)
    {
        if ($code) {
            $tokenData = TwitchApi::getAccessObject($code);

            if (isset($tokenData['access_token'])) {
                $response = TwitchApi::authChannel($tokenData['access_token']);

                if ($response['_id']) {
                    $followers = TwitchApi::followers($response['_id'], []);
                    $streams = TwitchApi::streams(['client_id' => config('twitch-api.client_id')]);
                    dd($streams);

                    $user = new User([
                        'name' => $response['display_name'],
                        'email' => $response['email'],
                        'password' => bcrypt('password'),
                        'description' => $response['description'],
                        'nickname' => $response['name'],
                        'role_id' => 0,
                        'followers_count' => $followers['_total']
                    ]);
                    $user->save();

                    $authProvider = AuthProvider::query()->updateOrCreate(
                        [
                            'user_id' => $user['id'],
                            'provider' => 'twitch'
                        ],
                        [
                            'user_id' => $user['id'],
                            'provider' => 'twitch',
                            'access_token' => $tokenData['access_token'],
                            'expires_in' => $tokenData['expires_in'],
                            'refresh_token' => $tokenData['refresh_token']
                        ]
                    );

                    return self::success('Authenticated');
                } else {
                    return self::error('Identification missed');
                }
            } else {
                return self::error('Access token missed');
            }
        } else {
            return self::error('Code missed');
        }
    }

    public static function error($message)
    {
        return [
            'success' => false,
            'message' => $message
        ];
    }

    public static function success($message)
    {
        return [
            'success' => true,
            'message' => $message
        ];
    }

}
