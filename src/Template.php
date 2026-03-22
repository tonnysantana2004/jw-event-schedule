<?php

namespace JWES;

class Template
{

    public function init()
    {

        $this->single_template();
        $this->archive_template();
    }
    public function single_template()
    {

        add_action(
            'single_template',
            function () {

                $singleTemplate = JWES_PLUGIN_DIR . '/templates/single-event.php';

                if (is_singular('event')) {

                    if (file_exists($singleTemplate)) {
                        return $singleTemplate;
                    }
                }
            }
        );
    }

    public function archive_template()
    {

        add_action(
            'archive_template',
            function () {
                
                $archiveTemplate = JWES_PLUGIN_DIR . '/templates/archive-event.php';

                if (is_post_type_archive('event') || is_tax('event_type')) {

                    if (file_exists($archiveTemplate)) {
                        return $archiveTemplate;
                    }
                }
            }
        );
    }
}
