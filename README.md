# Reproducer for bug with mocked services

_Don't use this for anything that you care about... It's a reproducer, not a 
use-in-production-because-its-cool - There be dragons!_

## Meta

1) `symfony new --webapp`
2) Created a controller, a simple service, and a `WebTestCase`

## The Bug:

When using `WebTestCase` to test a controller method that has a service injected,
often times its better to replace the actual service with a mock (e.g. we don't
want to call GitHub's API in a test...). But with a typical test flow like:

```php
public function testMethod(): void
{
    // Init the client
    
    // Create the mock
    
    // Set the mock in the container
    
    // 1st call to the controller (to get a form)
    
    // 2nd call to the controller (to submit the form)
}
```

When you call the controller for a 2nd time using the same client, the mocked service
is no longer available - instead the actual service is provided...

## See it in action:

Simple test that checks the class name of the service contains "Mock"

1) `bin/phpunit`

Test fails: 

```
...
) matches selector "h1" and the text "Submitted: App\Service\ACoolService" of the node matching selector "h1" contains "Form Submitted: Mock_".

/Users/jrdev-mac/develop/php/bug-mock-service/vendor/symfony/framework-bundle/Test/DomCrawlerAssertionsTrait.php:44
/Users/jrdev-mac/develop/php/bug-mock-service/tests/SomeControllerTest.php:32
/Users/jrdev-mac/develop/php/bug-mock-service/vendor/phpunit/phpunit/phpunit:107
```

Test failed on the 2nd call to the controller...
