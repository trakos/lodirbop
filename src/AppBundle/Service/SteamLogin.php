<?php

namespace Trakos\AppBundle\Service;


use Paza\OpenID\LightOpenID;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Trakos\AppBundle\Structures\SteamAuthResult;

class SteamLogin
{
    /**
     * @var LightOpenID
     */
    protected $openIdConsumer;

    /**
     * @var RouterInterface
     */
    protected $router;

    protected $locale = 'xx';

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->locale = $event->getRequest()->getLocale();
        $this->openIdConsumer = new LightOpenID($event->getRequest()->getHttpHost());
        $this->openIdConsumer->setIdentity('http://steamcommunity.com/openid');
        $this->openIdConsumer->setReturnUrl($this->router->generate(
            'steamOpenId',
            ['_locale' => $this->locale],
            RouterInterface::ABSOLUTE_PATH
        ));
    }

    public function getAuthUrl()
    {
        return $this->router->generate(
            'steamOpenId',
            ['_locale' => $this->locale]
        );
        //$this->openIdConsumer->authUrl();
    }

    /**
     * @return SteamAuthResult
     */
    public function auth()
    {
        $result = new SteamAuthResult();
        $result->success = true;
        $result->steamId = '76561197968941097';
        //$result->errorMessage = 'testowa wiadomość';
        return $result;
        $this->openIdConsumer->setData($_GET);
        if (!$this->openIdConsumer->isResponseState()) {
            $result->errorMessage = "Response is not a correct OpenId response";
        } else if ($this->openIdConsumer->checkUserCancelled()) {
            $result->errorMessage = "Steam authorization cancelled by user";
        } else {
            try {
                if (!@$this->openIdConsumer->validate()) {
                    $result->errorMessage = "Steam authorization failed";
                    return $result;
                }
            } catch (\Exception $e) {
                $result->errorMessage = $e->getMessage();
                return $result;
            }
            $matches = [];
            if (!preg_match('/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/', $this->openIdConsumer->getClaimedId(), $matches) || !$matches[1]) {
                $result->errorMessage = "Steam authorization returned unknown id";
                return $result;
            }
            $result->steamId = $matches[1];
            $result->success = true;
        }
        return $result;
    }
}