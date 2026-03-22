<?php

namespace JWES;

class Template
{
    public function singleEvent($originalTemplate)
    {
        $singleTemplate = JWES_PLUGIN_DIR .'/templates/single-event.php';

        if ('event' === get_post_type(get_the_ID())) {
            if (file_exists($singleTemplate)) {
                return $singleTemplate;
            }
        }

        return $originalTemplate;
    }

}
