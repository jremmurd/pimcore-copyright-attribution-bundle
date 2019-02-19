<?php

namespace JRemmurd\CopyrightAttributionBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

/**
 * Class CopyrightAttributionBundle
 * @package JRemmurd\CopyrightAttributionBundle
 */
class CopyrightAttributionBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            "/bundles/copyrightattribution/js/bundle.js",
            "/bundles/copyrightattribution/js/pimcore/authorCreditsTab.js"
        ];
    }

    public function getCssPaths()
    {
        return [
            "/bundles/copyrightattribution/css/style.css",
        ];
    }


    /**
     * @return string
     */
    public function getNiceName()
    {
        return "Copyright Attribution Bundle";
    }
}
