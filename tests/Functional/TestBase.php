<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TestBase extends WebTestCase
{
    use FixturesTrait;

    protected const FORMAT = 'jsonld';

    protected const  ID_AUTHENTICATE = '0f6acbf3-a958-4d2e-9352-bd17f469b002';

    protected static ?KernelBrowser $client = null;

    /**
     * @throws ToolsException
     */
    public function setUp()
    {
        if (null === self::$client) {
            self::$client = static::createClient();
        }

     //   $this->resetDatabase();

    }

    protected function getResponseData(Response $response): array
    {
        return \json_decode($response->getContent(), true);
    }

    /**
     * @throws ToolsException
     */
    private function resetDatabase(): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        if (!isset($metadata)) {
            $metadata = $em->getMetadataFactory()->getAllMetadata();
        }

        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();


        $this->postFixtureSetup();
        $this->loadFixtures([AppFixtures::class]);
    }
}
