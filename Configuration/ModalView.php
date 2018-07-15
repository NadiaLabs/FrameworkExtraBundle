<?php

namespace Nadia\Bundle\FrameworkExtraBundle\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * The ModalView class handles the ModalView annotation parts.
 *
 * @Annotation
 */
class ModalView implements ConfigurationInterface
{
    /**
     * Modal constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        foreach ($values as $k => $v) {
            $name = 'set'.$k;

            if (!method_exists($this, $name)) {
                throw new \RuntimeException(sprintf('Unknown key "%s" for annotation "@%s".', $k, get_class($this)));
            }

            $this->$name($v);
        }
    }

    /**
     * The view name.
     *
     * @var string
     */
    private $view;

    /**
     * The modal view name.
     *
     * @var string
     */
    private $modalView;

    /**
     * Modal format name for request attribute: "_format"
     *
     * @var bool
     */
    private $format = 'modal.html';

    /**
     * Get template for view rendering
     *
     * @param string $format The request's "_format" attribute value
     *
     * @return string
     */
    public function getTemplate($format)
    {
        if ($format === $this->format) {
            return $this->modalView;
        }

        return $this->view;
    }

    /**
     * @param string $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * @param string $modalView
     */
    public function setModalView($modalView)
    {
        $this->modalView = $modalView;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     */
    public function getAliasName()
    {
        return 'modal_view';
    }

    /**
     * {@inheritdoc}
     */
    public function allowArray()
    {
        return false;
    }
}
