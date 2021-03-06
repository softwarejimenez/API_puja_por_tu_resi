<?php
// tests/AppBundle/Controller/LuckyControllerTest.php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LuckyControllerTest extends WebTestCase
{
    public function testhelloo()
    {
        $client = static::createClient();
        $name="name";
        $client->request('GET', '/lucky/helloo/'.$name);
        // the HttpKernel response instance
        $response = $client->getResponse()->getContent();
        $this->assertEquals(
            '<html><body>Hello how are you'.$name.'</body></html>',
            $response
        );
        // Assert a specific 200 status code
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
    }
}
