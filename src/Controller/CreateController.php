<?php

namespace App\Controller;

use AmoCRM\Client\AmoCRMApiClient;
use App\Model\Contact;
use App\Model\Lead;
use App\Model\OAuth;
use App\View\View;
use Exception;
use Laminas\Diactoros\Response;
use League\OAuth2\Client\Token\AccessTokenInterface;

class CreateController extends Controller
{
    public function __invoke(): Response
    {
        include_once __DIR__ . '/../../config.php';
        $apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
        // Получение токена
        try {
            $accessToken = OAuth::getToken($apiClient);
        } catch (Exception $e) {
            echo "Ошибка получения токена";
        }

        $apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
            ->onAccessTokenRefresh(
                function (AccessTokenInterface $accessToken, string $baseDomain) {
                    OAuth::saveToken(
                        [
                            'accessToken' => $accessToken->getToken(),
                            'refreshToken' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'baseDomain' => $baseDomain,
                        ]
                    );
                }
            );

        // Создание контакта и сделки
        $contact = Contact::createContact($apiClient, $_POST['name'], $_POST['email'], $_POST['phone']);
        $leadsCollection = Lead::createLead($apiClient, $contact, $_POST['price']);

        // Рендер html
        return View::render('success');
    }
}