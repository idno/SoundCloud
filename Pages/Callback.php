<?php

    /**
     * Soundcloud pages
     */

    namespace IdnoPlugins\SoundCloud\Pages {

        /**
         * Default class to serve the Soundcloud callback
         */
        class Callback extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($soundcloud = \Idno\Core\site()->plugins()->get('SoundCloud')) {
                    if ($soundcloudAPI = $soundcloud->connect()) {
                        /* @var \Services_Soundcloud $soundcloudAPI */
                        if ($access_token = $soundcloudAPI->accessToken($this->getInput('code'))) {
                            $user = \Idno\Core\site()->session()->currentUser();
                            $user->soundcloud = array('access_token' => $access_token);
                            $user->save();
                            \Idno\Core\site()->session()->addMessage('Your Soundcloud account was connected.');
                        }
                    }
                }
                if (!empty($_SESSION['onboarding_passthrough'])) {
                    unset($_SESSION['onboarding_passthrough']);
                    $this->forward(\Idno\Core\site()->config()->getURL() . 'begin/connect-forwarder');
                }
                $this->forward('/account/soundcloud/');
            }

        }

    }