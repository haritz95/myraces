<?php

namespace App\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class StravaProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopeSeparator = ',';

    protected $scopes = ['read', 'activity:read_all'];

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://www.strava.com/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://www.strava.com/oauth/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://www.strava.com/api/v3/athlete', [
            'headers' => ['Authorization' => 'Bearer '.$token],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function mapUserToObject(array $user): User
    {
        $firstName = $user['firstname'] ?? '';
        $lastName = $user['lastname'] ?? '';

        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'name' => trim($firstName.' '.$lastName),
            'email' => $user['email'] ?? null,
            'avatar' => $user['profile'] ?? null,
        ]);
    }
}
