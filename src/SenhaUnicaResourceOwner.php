<?php

namespace Uspdev;

use Symfony\Component\OptionsResolver\OptionsResolver;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GenericOAuth1ResourceOwner;

use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use HWI\Bundle\OAuthBundle\Security\OAuthErrorHandler;
use HWI\Bundle\OAuthBundle\Security\OAuthUtils;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SenhaUnicaResourceOwner extends GenericOAuth1ResourceOwner
{
   protected $paths = array(
        'identifier' => 'loginUsuario',
        'nickname' => 'loginUsuario',
        'firstname' => 'nomeUsuario',
        'lastname' => null,
        'realname' => 'nomeUsuario',
        'email' => 'emailPrincipalUsuario',
        'profilepicture' => null,
    );

    public function getUserInformation(array $accessToken, array $extraParameters = array())
    {
        $parameters = array_merge([
            'oauth_consumer_key' => $this->options['client_id'],
            'oauth_timestamp' => time(),
            'oauth_nonce' => $this->generateNonce(),
            'oauth_version' => '1.0',
            'oauth_signature_method' => $this->options['signature_method'],
            'oauth_token' => $accessToken['oauth_token'],
        ], $extraParameters);
        $url = $this->options['infos_url'];
        $parameters['oauth_signature'] = OAuthUtils::signRequest(
            'POST',
            $url,
            $parameters,
            $this->options['client_secret'],
            $accessToken['oauth_token_secret'],
            $this->options['signature_method']
        );
        $content = $this->doGetUserInformationRequest($url, $parameters);

        // developers: uncomment this lines to inspect data returned by USP
        //echo "<pre>"; print_r((string) $content->getBody()); die();

        $response = $this->getUserResponse();
        $response->setData($content instanceof ResponseInterface ? (string) $content->getBody() : $content);
        $response->setResourceOwner($this);
        $response->setOAuthToken(new OAuthToken($accessToken));

        return $response;
    }

    protected function doGetUserInformationRequest($url, array $parameters = array())
    {
        return $this->httpRequest($url, null, array(), 'POST', $parameters);
    }

    public function getAuthorizationUrl($redirectUri, array $extraParameters = array())
    {
        $token = $this->getRequestToken($redirectUri, $extraParameters);

        return $this->normalizeUrl($this->options['authorization_url'], 
                      array('oauth_token' => $token['oauth_token'], 
                            'callback_id' => getenv('SENHAUNICA_CALLBACK_ID')));
    }


    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'authorization_url' => 'https://uspdigital.usp.br/wsusuario/oauth/authorize',
            'request_token_url' => 'https://uspdigital.usp.br/wsusuario/oauth/request_token',
            'access_token_url' => 'https://uspdigital.usp.br/wsusuario/oauth/access_token',
            'infos_url' => 'https://uspdigital.usp.br/wsusuario/oauth/usuariousp',
        ));
    }

}
