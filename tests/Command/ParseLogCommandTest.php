<?php

declare(strict_types=1);

namespace Tests\Command;

use App\Entity\Log;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ParseLogCommandTest extends KernelTestCase
{
    use ReloadDatabaseTrait;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function testParseSuccess()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('parse-log');
        $commandTester = new CommandTester($command);

        $commandTester->execute(['file' => 'logs.txt']);

        $logCount = $this->entityManager
            ->getRepository(Log::class)->createQueryBuilder('l')
            ->select('count(l.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(20, $logCount);
    }

    public function testParseWithFileParameterMissing()
    {
        $this->expectException(\Symfony\Component\Console\Exception\RuntimeException::class);

        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('parse-log');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
