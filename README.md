Paloma Shop Bundle
=====

# Installation

Install the bundle using composer:

```
composer require paloma/shop-bundle
```

# Configuration

TODO 

```yaml
security:
  
  providers:
  
    paloma_shop.security.user_provider:
      id: paloma_shop.security.user_provider
    
  firewalls:
    
    # ...
      
    paloma_api:

      pattern: ^/api/

      anonymous: true

      guard:
        authenticators:
          - Paloma\ShopBundle\Security\HttpJsonAuthenticator
    
    paloma_main:
    
      anonymous: true
      
      guard:
        authenticators:
          - Paloma\ShopBundle\Security\LoginFormAuthenticator
          
      logout:
        path: paloma_security_logout

      remember_me:
        secret: '%kernel.secret%'
        lifetime: 604800
        path: /
        always_remember_me: true
        secure: true
      
  access_control:
    # TODO
```