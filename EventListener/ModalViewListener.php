<?php

namespace Nadia\Bundle\FrameworkExtraBundle\EventListener;

use AppBundle\Configuration\ModalView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Handles the ModalView annotation for actions.
 *
 * Depends on pre-processing of the Sensio\Bundle\FrameworkExtraBundle\EventListener\ControllerListener.
 */
class ModalViewListener implements EventSubscriberInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * ModalViewListener constructor.
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
        $modalView = $request->attributes->get('_modal_view');

        if (!$modalView instanceof ModalView) {
            return;
        }

        if (null === $this->twig) {
            throw new \LogicException('You can not use the "@ModalView" annotation if the Twig Bundle is not available.');
        }

        $parameters = $event->getControllerResult();
        $template = $modalView->getTemplate($request->getRequestFormat());

        $event->setResponse(new Response($this->twig->render($template, $parameters)));
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
