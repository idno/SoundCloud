<?php

    namespace IdnoPlugins\Soundcloud {

        class Main extends \Idno\Common\Plugin {

            function registerPages() {
                // Register the deauth URL
                \Idno\Core\site()->addPageHandler('soundcloud/deauth/?','\IdnoPlugins\SoundCloud\Pages\Deauth');
                // Register the callback URL
                    \Idno\Core\site()->addPageHandler('soundcloud/callback/?','\IdnoPlugins\SoundCloud\Pages\Callback');
                // Register admin settings
                    \Idno\Core\site()->addPageHandler('admin/soundcloud/?','\IdnoPlugins\SoundCloud\Pages\Admin');
                // Register settings page
                    \Idno\Core\site()->addPageHandler('account/soundcloud/?','\IdnoPlugins\SoundCloud\Pages\Account');

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

                // Push "media" to Soundcloud
                \Idno\Core\site()->addEventHook('post/media/soundcloud',function(\Idno\Core\Event $event) {
                    $eventdata = $event->data();
                    $object = $eventdata['object'];
                    if ($attachments = $object->getAttachments()) {
                        foreach($attachments as $attachment) {
                            if ($this->hasSoundcloud()) {
                                if ($soundcloudAPI = $this->connect()) {
                                    $soundcloudAPI->setAccessToken(\Idno\Core\site()->session()->currentUser()->soundcloud['access_token']['access_token']);

                                    if ($bytes = \Idno\Entities\File::getFileDataFromAttachment($attachment)) {
                                        $media = '';
                                        $filename = tempnam(sys_get_temp_dir(), 'knownsoundcloud') . '.' . pathinfo($attachment['filename'], PATHINFO_EXTENSION);;
                                        file_put_contents($filename, $bytes);
                                        $media .= "@{$filename}"; //;type=" . $attachment['mime_type'] . ';filename=' . $attachment['filename'];
                                    }

                                    $message = strip_tags($object->getDescription());
									$message .= "\n\nOriginal: " . $object->getURL();
                                    try {
                                        $track = json_decode($soundcloudAPI->post('tracks', array(
                                            'track[title]' => $object->getTitle(),
                                            'track[asset_data]' => $media,
                                            'track[description]' => $message
                                        )));
                                        if (!empty($track->permalink_url)) {
                                            $result['id'] = $track->id;
                                        	$object->setPosseLink('soundcloud',$track->permalink_url);
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
                if (!$soundcloud->hasSoundcloud()) {
                    if ($soundcloudAPI = $soundcloud->connect()) {
                        /* @var \Services_Soundcloud $soundcloudAPI */
                        $login_url = $soundcloudAPI->getAuthorizeUrl();
                    }
                } else {
                    $login_url = '';
                }
                return $login_url;

            }

            /**
             * Connect to Soundcloud
             * @return bool|\Soundcloud
             */
            function connect() {
                if (!empty(\Idno\Core\site()->config()->soundcloud)) {
                    require_once(dirname(__FILE__) . '/external/php-soundcloud/Services/Soundcloud.php');
                    $soundcloud = new \Services_Soundcloud(
                        \Idno\Core\site()->config()->soundcloud['clientId'],
                        \Idno\Core\site()->config()->soundcloud['clientSecret'],
                        \Idno\Core\site()->config()->getURL() . 'soundcloud/callback'
                    );
                    return $soundcloud;
                }
                return false;
            }

            /**
             * Can the current user use Twitter?
             * @return bool
             */
            function hasSoundcloud() {
               return \Idno\Core\site()->session()->currentUser()->soundcloud;
            }

        }

    }
