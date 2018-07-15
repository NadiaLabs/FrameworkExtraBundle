<?php

namespace Nadia\Bundle\FrameworkExtraBundle\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * The ViewSwitch class handles the ViewSwitch annotation parts.
 *
 * @Annotation
 */
class ViewSwitch implements ConfigurationInterface
{
    /**
     * ViewSwitch constructor.
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
     * The format value for request attribute: "_format"
     *
     * @var string
     */
    private $format;

    /**
     * @return string
     */
    public function getView()
    {
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
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
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
        return 'view_switch';
    }

    /**
     * {@inheritdoc}
     */
    public function allowArray()
    {
        return true;
    }
}
