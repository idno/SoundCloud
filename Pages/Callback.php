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
                        if ($access_code = $soundcloudAPI->accessToken($this->getInput('code'))) {
                            $soundcloudAPI->setAccessToken($access_code['access_token']);
                            $user_details = $soundcloudAPI->get('me');
                            $user_details = json_decode($user_details,true);
                            $user = \Idno\Core\site()->session()->currentUser();
                            $username = $user_details['permalink'];
                            \Idno\Core\site()->syndication()->registerServiceAccount('soundcloud', $user_details['permalink'], 'SoundCloud: ' . $user_details['username']);
                            $user->soundcloud[$user_details['permalink']] = array('access_token' => $access_code['access_token'], 'username' => $user_details['username']);
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