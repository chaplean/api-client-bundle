<?php

namespace Chaplean\Bundle\ApiClientBundle\Utility;

use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\PlainResponse;
use Chaplean\Bundle\ApiClientBundle\Tests\Resources\DataProviderTrait;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class EmailUtilityTest.
 *
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class EmailUtilityTest extends MockeryTestCase
{
    use DataProviderTrait;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->mailer = \Mockery::mock(\Swift_Mailer::class);
        $this->translator = \Mockery::mock(TranslatorInterface::class);
        $this->templating = \Mockery::mock(TwigEngine::class);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::isStatusCodeConfiguredForNotifications()
     *
     * @dataProvider statusCodeAndConfigurationForNotificationChecks
     *
     * @param integer $code
     * @param array   $config
     * @param boolean $expectedResult
     *
     * @return void
     */
    public function testIsStatusCodeConfiguredForNotifications($code, array $config, $expectedResult)
    {
        $this->mailer = \Mockery::mock(\Swift_Mailer::class);
        $this->translator = \Mockery::mock(TranslatorInterface::class);
        $this->templating = \Mockery::mock(TwigEngine::class);

        $config = [
            'enable_email_logging' => null,
            'email_logging'        => [
                'codes_listened' => $config,
                'address_from'   => 'test@example.com',
                'address_to'     => 'test@example.com'
            ],
        ];

        $utility = new EmailUtility($config, $this->mailer, $this->translator, $this->templating);
        $actualResult = $utility->isStatusCodeConfiguredForNotifications($code);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::sendRequestExecutedNotificationEmail()
     *
     * @return void
     */
    public function testSendMailIfEnabledAndCodeOk()
    {
        $this->translator->shouldReceive('trans')->once();
        $this->templating->shouldReceive('render')->once();
        $this->mailer->shouldReceive('send')->once();

        $config = [
            'enable_email_logging' => null,
            'email_logging'        => [
                'codes_listened' => ['0', '4XX', '5XX'],
                'address_from'   => 'test@example.com',
                'address_to'     => 'test@example.com'
            ],
        ];

        $utility = new EmailUtility($config, $this->mailer, $this->translator, $this->templating);
        $utility->sendRequestExecutedNotificationEmail(new PlainResponse(new Response(501, [], ''), 'get', 'url', []));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::sendRequestExecutedNotificationEmail()
     *
     * @return void
     */
    public function testDontSendMailIfEnabledButCodNotOk()
    {
        $this->mailer->shouldNotReceive('send');

        $config = [
            'enable_email_logging' => null,
            'email_logging'        => [
                'codes_listened' => ['0', '4XX', '5XX'],
                'address_from'   => 'test@example.com',
                'address_to'     => 'test@example.com'
            ],
        ];

        $utility = new EmailUtility($config, $this->mailer, $this->translator, $this->templating);
        $utility->sendRequestExecutedNotificationEmail(new PlainResponse(new Response(204, [], ''), 'get', 'url', []));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::__construct()
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Email logging is enabled, you must register the mailer, translator and twig services
     *
     * @return void
     */
    public function testConstructFailsIfConfigEnablesLoggingWithoutTheRequiredServices()
    {
        $config = [
            'enable_email_logging' => null,
            'email_logging'        => [
                'codes_listened' => ['0', '4XX', '5XX'],
                'address_from'   => 'test@example.com',
                'address_to'     => 'test@example.com'
            ],
        ];

        new EmailUtility($config);
    }
}
