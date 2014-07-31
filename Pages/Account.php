<?php

    /**
     * Soundcloud pages
     */

    namespace IdnoPlugins\SoundCloud\Pages {

        /**
         * Default class to serve Soundcloud-related account settings
         */
        class Account extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($soundcloud = \Idno\Core\site()->plugins()->get('SoundCloud')) {
                    $login_url = $soundcloud->getAuthURL();
                }
                $t = \Idno\Core\site()->template();
                $body = $t->__(['login_url' => $login_url])->draw('account/soundcloud');
                $t->__(['title' => 'Soundcloud', 'body' => $body])->drawPage();
            }

            function postContent() {
                $this->gatekeeper(); // Logged-in users only
                if (($this->getInput('remove'))) {
                    $user = \Idno\Core\site()->session()->currentUser();
                    $user->soundcloud = [];
                    $user->save();
                    \Idno\Core\site()->session()->addMessage('Your SoundCloud settings have been removed from your account.');
                }
                $this->forward('/account/soundcloud/');
            }

        }

    }