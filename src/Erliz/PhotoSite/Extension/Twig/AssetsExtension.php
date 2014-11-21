<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   21.11.14
 */

namespace Erliz\PhotoSite\Extension\Twig;


use Twig_SimpleFunction;

class AssetsExtension extends ApplicationAwareExtension
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Assets';
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('asset', array($this, 'asset'))
        );
    }

    /**
     * @param string $path
     * @param bool   $withVersion
     *
     * @return string
     */
    public function asset($path, $withVersion = true)
    {
        $staticSettings = $this->getApp()['config']['static'];

        return sprintf(
            '%s://%s/%s%s',
            $staticSettings['scheme'],
            $staticSettings['host'],
            $path,
            $withVersion ? '?' . $staticSettings['version'] : ''
        );
    }
}
