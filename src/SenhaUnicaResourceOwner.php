<?php

namespace Uspdev;

use Symfony\Component\OptionsResolver\OptionsResolver;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GenericOAuth1ResourceOwner;

class SenhaUnicaResourceOwner extends GenericOAuth1ResourceOwner
{
    /**
     * {@inheritdoc}
     */
    public function getUserInformation(array $accessToken, array $extraParameters = array())
    {
        return parent::getUserInformation($accessToken, $extraParameters);
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

//TESTE
    public function getData() {
        return $this->data;
    }

    public function getRoles() {
        return array('ROLE_OAUTH_USER');
    }
}
