<?php
declare(strict_types = 1);
namespace App\Tests\Functional;

use App\Bundle\ClockMockBundle;
use App\Bundle\TestDoubleBundle;
use App\Client\BambooClient;
use App\Client\GeneralClient;
use App\Extractor\DeploymentInformation;
use GuzzleHttp\Psr7\Response;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DocsToBambooControllerTest extends KernelTestCase
{
    /**
     * @test
     */
    public function bambooBuildIsTriggered()
    {
        $generalClientProphecy = $this->prophesize(GeneralClient::class);
        $generalClientProphecy
            ->request('GET', 'https://raw.githubusercontent.com/TYPO3-Documentation/TYPO3CMS-Reference-CoreApi/latest/composer.json')
            ->shouldBeCalled()
            ->willReturn(new Response(200, [], file_get_contents(__DIR__ . '/Fixtures/DocsToBambooGoodRequestComposer.json')));
        TestDoubleBundle::addProphecy(GeneralClient::class, $generalClientProphecy);

        $bambooClientProphecy = $this->prophesize(BambooClient::class);
        $bambooClientProphecy
            ->post(
                file_get_contents(__DIR__ . '/Fixtures/DocsToBambooGoodBambooPostUrl.txt'),
                require __DIR__ . '/Fixtures/DocsToBambooGoodBambooPostData.php'
            )->shouldBeCalled()
            ->willReturn(new Response());
        TestDoubleBundle::addProphecy(BambooClient::class, $bambooClientProphecy);

        $kernel = new \App\Kernel('test', true);
        $kernel->boot();
        DatabasePrimer::prime($kernel);

        ClockMockBundle::register(DeploymentInformation::class);
        ClockMockBundle::withClockMock(155309515.6937);

        $request = require __DIR__ . '/Fixtures/DocsToBambooGoodRequest.php';
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);
    }

    /**
     * @test
     */
    public function bambooBuildIsNotTriggered()
    {
        $bambooClientProphecy = $this->prophesize(BambooClient::class);
        $bambooClientProphecy->post(Argument::cetera())->shouldNotBeCalled();
        TestDoubleBundle::addProphecy(BambooClient::class, $bambooClientProphecy);

        $kernel = new \App\Kernel('test', true);
        $kernel->boot();
        $request = require __DIR__ . '/Fixtures/DocsToBambooBadRequest.php';
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);
    }

    public function bambooBuildIsNotTriggeredDueToMissingDependency(): void
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();
        DatabasePrimer::prime($kernel);

        $generalClientProphecy = $this->prophesize(GeneralClient::class);
        $generalClientProphecy
            ->request('GET', 'https://raw.githubusercontent.com/TYPO3-Documentation/TYPO3CMS-Reference-CoreApi/latest/composer.json')
            ->shouldBeCalled()
            ->willReturn(new Response(200, [], file_get_contents(__DIR__ . '/Fixtures/DocsToBambooBadRequestComposerWithoutDependency.json')));
        TestDoubleBundle::addProphecy(GeneralClient::class, $generalClientProphecy);

        $request = require __DIR__ . '/Fixtures/DocsToBambooGoodRequest.php';
        $response = $kernel->handle($request);
        $this->assertSame('Dependencies are not fulfilled. See https://intercept.typo3.com for more information.', $response->getContent());
        $this->assertSame(412, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function githubPingIsHandled()
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();
        DatabasePrimer::prime($kernel);

        $request = require __DIR__ . '/Fixtures/DocsToBambooGithubPingRequest.php';
        $response = $kernel->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('github ping', $response->getContent());
        $kernel->terminate($request, $response);
    }
}
