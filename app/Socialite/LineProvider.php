<?php

namespace App\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class LineProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['profile'];
    
    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://access.line.me/oauth2/v2.1/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://api.line.me/oauth2/v2.1/token';
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code'          => $code,
            'redirect_uri'  => $this->redirectUrl,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl = 'https://api.line.me/v2/profile';
        $response = $this->getHttpClient()->get(
            $userUrl, $this->getRequestOptions($token)
        );
        $user = json_decode($response->getBody(), true);
        return $user;
    }
    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'user_id' => $user['userId'],
            'user_name' => $user['displayName'],
            'nickname' => $user['displayName'],
            'email' => null,
            'user_image' => $user['pictureUrl'],
            'avatar_original' => $user['pictureUrl'],
        ]);
    }
    /**
     * Get the default options for an HTTP request.
     *
     * @return array
     */
    protected function getRequestOptions($token)
    {
        return [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ];
    }
}