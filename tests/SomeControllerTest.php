<?php

use App\Service\ACoolService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SomeControllerTest extends WebTestCase
{
    public function testSomeController(): void
    {
        $mockService = $this->createMock(ACoolService::class);
        $mockService
            ->expects(self::once())
            ->method('doSomething')
            ->willReturn(true)
        ;

        // Init the Client and set the mock service
        $client = static::createClient();
//        $client->disableReboot(); <--- This allows you to keep the same container between calls

        static::getContainer()->set(ACoolService::class, $mockService);

        // 1st Call - The mockService is passed to the controller
        $client->request('GET', '/');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Get Form: Mock_');

        // 2nd Call - The actual service is passed to the controller (Expecting MockService)
        $client->submitForm('Save', [
            'bug_form[bug]' => 'The form does not matter',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Submitted: Mock_');
    }
}