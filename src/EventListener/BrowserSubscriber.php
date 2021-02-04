<?php 

namespace App\EventListener;

use App\AppLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class BrowserSubscriber implements EventSubscriberInterface 
{
    public static function getSubscribedEvents() {
        return array(
            KernelEvents::RESPONSE => array(
                array('clearBrowserCache', 434255),
            ),
        );
    }

    public function clearBrowserCache(ResponseEvent $event) {
        $response = $event->getResponse();

        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('max-age', 0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);        
    }
}
?>