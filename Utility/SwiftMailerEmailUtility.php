<?php

namespace Chaplean\Bundle\ApiClientBundle\Utility;

use Chaplean\Bundle\ApiClientBundle\Api\ResponseInterface;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SwiftMailerEmailUtility.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Utility
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class SwiftMailerEmailUtility implements EmailUtilityInterface
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var TwigEngine
     */
    protected $templating;

    /**
     * ApiLogUtility constructor.
     *
     * @param array               $parameters
     * @param \Swift_Mailer       $mailer
     * @param TranslatorInterface $translator
     * @param TwigEngine          $templating
     */
    public function __construct(array $parameters, \Swift_Mailer $mailer = null, /** TranslatorInterface */ $translator = null, TwigEngine $templating = null)
    {
        $this->parameters = $parameters;

        if (array_key_exists('enable_email_logging', $this->parameters) && ($mailer === null || $translator === null || $templating === null)) {
            throw new \InvalidArgumentException('Email logging is enabled, you must register the mailer, translator and twig services');
        }

        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->templating = $templating;
    }

    /**
     * Persists in database a log entity representing the request just ran.
     *
     * @param ResponseInterface $response
     * @param string|null       $apiName
     *
     * @return void
     */
    public function sendRequestExecutedNotificationEmail(ResponseInterface $response, string $apiName = null)
    {
        if (!$this->isEnabledLoggingFor($apiName ?: '')) {
            return;
        }

        $code = $response->getCode();
        if (!$this->isStatusCodeConfiguredForNotifications($code)) {
            return;
        }

        $message = new \Swift_Message();
        $message
            ->setFrom($this->parameters['email_logging']['address_from'])
            ->setTo($this->parameters['email_logging']['address_to'])
            ->setSubject($this->translator->trans('chaplean_api_client.email.request_executed_notification.subject'))
            ->setBody(
                $this->templating->render('ChapleanApiClientBundle:Email:request_executed_notification.txt.twig', ['response' => $response])
            );

        $this->mailer->send($message);
    }

    /**
     * Compares the given $code against the configuration and tells if we need to send
     * a notification.
     *
     * @param integer $code
     *
     * @return boolean
     */
    public function isStatusCodeConfiguredForNotifications($code)
    {
        $code = (string) $code;
        $config = $this->parameters['email_logging']['codes_listened'];

        // Test exact match
        if (in_array($code, $config, true)) {
            return true;
        }

        // Test by familly (eg. 1XX, 2XX, ...)
        $code = $code[0] . 'XX';
        if (in_array($code, $config, true)) {
            return true;
        }

        return false;
    }

    /**
     * Check if email logging for $apiName is enabled
     *
     * @param string $apiName
     *
     * @return boolean
     * @deprecated Will be moved and refactored in 2.X in RequestExecutedListener::isEnabledLoggingFor(string, string)
     */
    public function isEnabledLoggingFor(string $apiName): bool
    {
        if (!array_key_exists('enable_email_logging', $this->parameters)) {
            return false;
        }

        if ($this->parameters['enable_email_logging'] === null) {
            return true;
        }

        $isEnabled = in_array($apiName, $this->parameters['enable_email_logging']['elements'], true);

        if ($this->parameters['enable_email_logging']['type'] === 'exclusive') {
            return !$isEnabled;
        }

        return $isEnabled;
    }
}
