<?php

    namespace IdnoPlugins\Soundcloud {

        class Main extends \Idno\Common\Plugin {

            function registerPages() {
                // Register the deauth URL
                    \Idno\Core\Idno::site()->routes()->addRoute('soundcloud/deauth/?','\IdnoPlugins\SoundCloud\Pages\Deauth');
                // Register the callback URL
                    \Idno\Core\Idno::site()->routes()->addRoute('soundcloud/callback/?','\IdnoPlugins\SoundCloud\Pages\Callback');
                // Register admin settings
                    \Idno\Core\Idno::site()->routes()->addRoute('admin/soundcloud/?','\IdnoPlugins\SoundCloud\Pages\Admin');
                // Register settings page
                    \Idno\Core\Idno::site()->routes()->addRoute('account/soundcloud/?','\IdnoPlugins\SoundCloud\Pages\Account');

                /** Template extensions */
                // Add menu items to account & administration screens
                    \Idno\Core\site()->template()->extendTemplate('admin/menu/items','admin/soundcloud/menu');
                    \Idno\Core\site()->template()->extendTemplate('account/menu/items','account/soundcloud/menu');
                    \Idno\Core\site()->template()->extendTemplate('onboarding/connect/networks','onboarding/connect/soundcloud');
            }

            function registerEventHooks() {

                \Idno\Core\site()->syndication()->registerService('soundcloud', function() {
                    return $this->hasSoundcloud();
                }, array('media'));

                \Idno\Core\Idno::site()->events()->addListener('user/auth/success',function(\Idno\Core\Event $event) {
                    if ($this->hasSoundcloud()) {
                        if (is_array(\Idno\Core\site()->session()->currentUser()->soundcloud)) {
                            foreach(\Idno\Core\site()->session()->currentUser()->soundcloud as $username => $details) {
                                if (!in_array($username, ['username','access_token'])) {
                                    \Idno\Core\site()->syndication()->registerServiceAccount('soundcloud', $username, $username);
                                }
                            }
                        }
                    }
                });

                // Push "media" to Soundcloud
                \Idno\Core\Idno::site()->events()->addListener('post/media/soundcloud',function(\Idno\Core\Event $event) {
                    $eventdata = $event->data();
                    $object = $eventdata['object'];
                    if ($attachments = $object->getAttachments()) {
                        foreach($attachments as $attachment) {
                            if ($this->hasSoundcloud()) {
                                if (!empty($eventdata['syndication_account'])) {
                                    $soundcloudAPI  = $this->connect($eventdata['syndication_account']);
                                    $user_details = \Idno\Core\site()->session()->currentUser()->soundcloud[$eventdata['syndication_account']];
                                    if (!empty($user_details['username'])) {
                                        $name = $user_details['username'];
                                    }
                                } else {
                                    $soundcloudAPI  = $this->connect();
                                    $user_details = \Idno\Core\site()->session()->currentUser()->soundcloud['access_token'];
                                }
                                if (empty($name)) {
                                    $name = 'SoundCloud';
                                }
                                if ($soundcloudAPI && !empty($user_details)) {
                                    $soundcloudAPI->setAccessToken($user_details['access_token']);

                                    if ($bytes = \Idno\Entities\File::getFileDataFromAttachment($attachment)) {
                                        $media = '';
                                        $filename = tempnam(sys_get_temp_dir(), 'knownsoundcloud') . '.' . pathinfo($attachment['url'], PATHINFO_EXTENSION);
                                        file_put_contents($filename, $bytes);
                                        if (version_compare(phpversion(), '5.5', '>=')) {
                                            $media = new \CURLFile($filename);
                                            if (!empty($attachment['mime_type'])) $media->setMimeType($attachment['mime_type']);
                                            if (!empty($attachment['filename'])) $media->setPostFilename($attachment['filename']);
                                        } else {
                                            $media .= "@{$filename}"; //;type=" . $attachment['mime_type'] . ';filename=' . $attachment['filename'];
                                        }
                                    }

                                    $message = strip_tags($object->getDescription());
				    $message .= "\n\nOriginal: " . $object->getURL();
				    $message = html_entity_decode($message);
                                    try {
                                        $track = json_decode($soundcloudAPI->post('tracks', array(
                                            'track[title]' => $object->getTitle(),
                                            'track[asset_data]' => $media,
                                            'track[description]' => $message
                                        )));
                                        if (!empty($track->permalink_url)) {
                                            $result['id'] = $track->id;
                                        	$object->setPosseLink('soundcloud',$track->permalink_url, $name);
                                        	$object->save();
                                        }
                                    } catch (\Exception $e) {
                                        \Idno\Core\site()->session()->addMessage('Could not post sound to SoundCloud: ' . $e->getMessage());
                                    }

                                    @unlink($filename);

                                }
                            }
                        }
                    }
                });
            }

            /**
             * Retrieve the URL to authenticate with the API
             * @return string
             */
            function getAuthURL() {

                $soundcloud = $this;
                $login_url = '';
                if ($soundcloudAPI = $soundcloud->connect()) {
                    /* @var \Services_Soundcloud $soundcloudAPI */
                    $login_url = $soundcloudAPI->getAuthorizeUrl(array('scope' => 'non-expiring'));
                }
                return $login_url;

            }

            /**
             * Connect to Soundcloud
             * @return bool|\Soundcloud
             */
            function connect($username = false) {
                require_once(dirname(__FILE__) . '/external/php-soundcloud/Services/Soundcloud.php');
                if (!empty(\Idno\Core\site()->config()->soundcloud)) {
                    if (!empty($username)) {
                        $params = \Idno\Core\site()->session()->currentUser()->soundcloud[$username];
                        $soundcloud = new \Services_Soundcloud(
                            \Idno\Core\site()->config()->soundcloud['clientId'],
                            \Idno\Core\site()->config()->soundcloud['clientSecret'],
                            \Idno\Core\site()->config()->getURL() . 'soundcloud/callback'
                        );
                        return $soundcloud;
                    } else if (!empty(\Idno\Core\site()->config()->soundcloud['clientId'])) {
                        $soundcloud = new \Services_Soundcloud(
                            \Idno\Core\site()->config()->soundcloud['clientId'],
                            \Idno\Core\site()->config()->soundcloud['clientSecret'],
                            \Idno\Core\site()->config()->getURL() . 'soundcloud/callback'
                        );
                        return $soundcloud;
                    }
                }
                return false;
            }

            /**
             * Can the current user use Twitter?
             * @return bool
             */
            function hasSoundcloud() {
                if (!\Idno\Core\site()->session()->currentUser()) {
                    return false;
                }
                return \Idno\Core\site()->session()->currentUser()->soundcloud;
            }

        }

    }
