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
        'nickname' => 'nomeUsuario',
    );
    public function getUserInformation(array $accessToken, array $extraParameters = array())
    {
	//$response = parent::getUserInformation($accessToken, $extraParameters);
        //$responseData = $response->getData(); 

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
        $response = $this->getUserResponse(); 
        $response->setData($content instanceof ResponseInterface ? (string) $content->getBody() : $content);
        $response->setResourceOwner($this); 
        $response->setOAuthToken(new OAuthToken($accessToken)); echo "<pre>"; var_dump($response->getData()); die();
$responseData = $response->getData(); 

	print_r($responseData); die();
    }

    protected function doGetUserInformationRequest($url, array $parameters = array())
    {
        return $this->httpRequest($url, null, array(), null, $parameters);
    }

    protected function httpRequest($url, $content = null, array $headers = array(), $method = null, array $parameters = array())
    {
        foreach ($parameters as $key => $value) {
            $parameters[$key] = $key.'="'.rawurlencode($value).'"';
        }
        if (!$this->options['realm']) {
            array_unshift($parameters, 'realm="'.rawurlencode($this->options['realm']).'"');
        }
        $headers['Authorization'] = 'OAuth '.implode(', ', $parameters);
        return parent::httpRequest($url, $content, $headers, $method);
    }

  
    public function getAuthorizationUrl($redirectUri, array $extraParameters = array())
    {
        $token = $this->getRequestToken($redirectUri, $extraParameters);

        return $this->normalizeUrl($this->options['authorization_url'], array('oauth_token' => $token['oauth_token'], 'callback_id' => getenv('SENHAUNICA_CALLBACK_ID')));
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
