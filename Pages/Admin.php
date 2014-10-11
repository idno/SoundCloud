<?php

    /**
     * Soundcloud pages
     */

    namespace IdnoPlugins\SoundCloud\Pages {

        /**
         * Default class to serve Soundcloud settings in administration
         */
        class Admin extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->adminGatekeeper(); // Admins only
                $t = \Idno\Core\site()->template();
                $body = $t->draw('admin/soundcloud');
                $t->__(array('title' => 'Soundcloud', 'body' => $body))->drawPage();
            }

            function postContent() {
                $this->adminGatekeeper(); // Admins only
                $appId = $this->getInput('clientId');
                $secret = $this->getInput('clientSecret');
                \Idno\Core\site()->config->config['soundcloud'] = array(
                    'clientId' => $appId,
                    'clientSecret' => $secret
                );
                \Idno\Core\site()->config()->save();
                \Idno\Core\site()->session()->addMessage('Your SoundCloud application details were saved.');
                $this->forward('/admin/soundcloud/');
            }

        }

    }