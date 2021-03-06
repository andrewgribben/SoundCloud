<?php

    /**
     * Plugin administration
     */

    namespace IdnoPlugins\SoundCloud\Pages {

        class Deauth extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($twitter = \Idno\Core\site()->plugins()->get('SoundCloud')) {
                    if ($user = \Idno\Core\site()->session()->currentUser()) {
                        if ($account = $this->getInput('remove')) {
                            if (array_key_exists($account, $user->soundcloud)) {
                                unset($user->soundcloud[$account]);
                            } else {
                                $user->soundcloud = false;
                            }
                        } else {
                            $user->soundcloud = false;
                        }
                        $user->save();
                        \Idno\Core\site()->session()->refreshSessionUser($user);
                        if (!empty($user->link_callback)) {
                            error_log($user->link_callback);
                            $this->forward($user->link_callback); exit;
                        }
                    }
                }
                $this->forward($_SERVER['HTTP_REFERER']);
            }

            function postContent() {
                $this->getContent();
            }

        }

    }