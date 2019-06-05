<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Utility;

use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\PlainResponse;
use Chaplean\Bundle\ApiClientBundle\Tests\Resources\DataProviderTrait;
use Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility;
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
     * @var \Mockery\MockInterface
     */
    private $mailer;

    /**
     * @var \Mockery\MockInterface
     */
    private $translator;

    /**
     * @var \Mockery\MockInterface
     */
    private $templating;

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

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::__construct
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsEnabledExplicitDefinition()
    {
        $utility = new EmailUtility(
            [
                'enable_email_logging' => [
                    'type'     => 'inclusive',
                    'elements' => [
                        'bar_api'
                    ]
                ]
            ],
            $this->mailer,
            $this->translator,
            $this->templating
        );

        $this->assertTrue($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsEnabledTildDefinition()
    {
        $utility = new EmailUtility(
            [
                'enable_email_logging' => null
            ],
            $this->mailer,
            $this->translator,
            $this->templating
        );

        $this->assertTrue($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsEnabledExclusiveDefinition()
    {
        $utility = new EmailUtility(
            [
                'enable_email_logging' => [
                    'type' => 'exclusive',
                    'elements' => [
                        'foo_api'
                    ]
                ]
            ],
            $this->mailer,
            $this->translator,
            $this->templating
        );

        $this->assertTrue($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsDisabledExplicitDefinition()
    {
        $utility = new EmailUtility(
            [
                'enable_email_logging' => [
                    'type' => 'exclusive',
                    'elements' => [
                        'bar_api'
                    ]
                ]
            ],
            $this->mailer,
            $this->translator,
            $this->templating
        );

        $this->assertFalse($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsDisabledByDefault()
    {
        $utility = new EmailUtility(
            [],
            $this->mailer,
            $this->translator,
            $this->templating
        );

        $this->assertFalse($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsDisabledNotDefineApiName()
    {
        $utility = new EmailUtility(
            [
                'enable_email_logging' => [
                    'type' => 'inclusive',
                    'elements' => [
                        'foo_api'
                    ]
                ]
            ],
            $this->mailer,
            $this->translator,
            $this->templating
        );

        $this->assertFalse($utility->isEnabledLoggingFor('bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility::sendRequestExecutedNotificationEmail
     *
     * @return void
     */
    public function testSendRequestExecutedNotificationEmailWithDisabledApi()
    {
        $this->mailer->shouldNotReceive('send');

        $config = [
            'enable_email_logging' => [
                'type'     => 'exclusive',
                'elements' => [
                    'foo_api'
                ]
            ]
        ];

        $utility = new EmailUtility($config, $this->mailer, $this->translator, $this->templating);
        $utility->sendRequestExecutedNotificationEmail(new PlainResponse(new Response(200, [], ''), 'get', 'url', []), 'foo_api');
    }
}
