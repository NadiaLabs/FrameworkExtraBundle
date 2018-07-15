<?php

namespace Nadia\Bundle\FrameworkExtraBundle\EventListener;

use Nadia\Bundle\FrameworkExtraBundle\Configuration\ViewSwitch;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Handles the ViewSwitch annotation for actions.
 *
 * Depends on pre-processing of the Sensio\Bundle\FrameworkExtraBundle\EventListener\ControllerListener.
 */
class ViewSwitchListener implements EventSubscriberInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * ViewSwitchListener constructor.
     *
     * @param \Twig_Environment|null $twig
     */
    public function __construct(\Twig_Environment $twig = null)
    {
        $this->twig = $twig;
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $switches = $request->attributes->get('_view_switch');
        $view = null;

        foreach ($switches as $switch) {
            if (!$switch instanceof ViewSwitch) {
                return;
            }
            if ($request->getRequestFormat() === $switch->getFormat()) {
                $view = $switch->getView();
                break;
            }
        }

        if (null === $view) {
            return;
        }

        if (null === $this->twig) {
            throw new \LogicException('You can not use the "@ViewSwitch" annotation if the Twig Bundle is not available.');
        }

        $parameters = $event->getControllerResult();

        $event->setResponse(new Response($this->twig->render($view, $parameters)));
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => 'onKernelView',
        ];
    }
}
