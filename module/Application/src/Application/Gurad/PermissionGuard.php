<?php

namespace Application\Guard;

use Zend\Http\Request as HttpRequest;
use Zend\Mvc\MvcEvent;
use ZfcRbac\Guard\AbstractGuard;

class PermissionGuard extends AbstractGuard
{
    const EVENT_PRIORITY = 100;

    /**
     * List of IPs to blacklist
     */
    protected $ipAddresses = [];

    /**
     * @param array $ipAddresses
     */
    public function __construct(array $ipAddresses)
    {
        $this->ipAddresses = $ipAddresses;
    }

    /**
     * @param  MvcEvent $event
     * @return bool
     */
    public function isGranted(MvcEvent $event)
    {
        $request = $event->getRequest();

        if (!$request instanceof HttpRequest) {
            return true;
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $clientIp = $_SERVER['REMOTE_ADDR'];
        }

        return !in_array($clientIp, $this->ipAddresses);
    }
}