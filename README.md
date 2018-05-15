    composer create-project symfony/skeleton usp

    composer require orm-pack annotations validator template security-bundle make form
    composer require hwi/oauth-bundle php-http/guzzle6-adapter php-http/httplug-bundle

    composer require server maker-bundle --dev 
  
    no .env: 
    DATABASE_URL=mysql://master:master@localhost:3306/senhaunica

    php bin/console make:controller IndexController


    php bin/console server:run


    {% if app.user %}
        logado
    {% elseif not app.user %}
       não logado
    {% endif %}
    
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Http\HttplugBundle\HttplugBundle(), // If you require the php-http/httplug-bundle package.
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
        );
    }


    config/routes.yaml

    hwi_oauth_redirect:
        resource: "@HWIOAuthBundle/Resources/config/routing/redirect.xml"
        prefix:   /connect

    hwi_oauth_login:
        resource: "@HWIOAuthBundle/Resources/config/routing/login.xml"
        prefix:   /login

    senhaunica_login:
        path:    /senhaunica/callback

    senhaunica_logout:
        path: /senhaunica/logout
        
        
        
   hwi_oauth:
    firewall_names: [secured_area]
    resource_owners:
        auth0:
            type:                oauth1
            class:               'Uspdev\SenhaUnicaResourceOwnerr'
            base_url:            https://localhost:8000
            client_id:           YOUR_CLIENT_ID
            client_secret:       YOUR_CLIENT_SECRET
            redirect_uri:        https://localhost:8000/callback
