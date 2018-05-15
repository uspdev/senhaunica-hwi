<?php

namespace Uspdev;

use Symfony\Component\OptionsResolver\OptionsResolver;

class SenhaUnicaResourceOwner extends GenericOAuth1ResourceOwner
{
    /**
     * {@inheritdoc}
     */
    protected $paths = array(
        'identifier' => 'id_str',
        'nickname' => 'screen_name',
        'realname' => 'name',
        'profilepicture' => 'profile_image_url_https',
        'email' => 'email',
    );

    /**
     * {@inheritdoc}
     */
    public function getUserInformation(array $accessToken, array $extraParameters = array())
    {
        if ($this->options['include_email']) {
            $this->options['infos_url'] = $this->normalizeUrl($this->options['infos_url'], array('include_email' => 'true'));
        }

        return parent::getUserInformation($accessToken, $extraParameters);
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

        $resolver->setDefined('x_auth_access_type');
        $resolver->setAllowedValues('x_auth_access_type', array('read', 'write'));
        $resolver->setAllowedTypes('include_email', 'bool');
    }
}
