<?php

    if ($soundcloud = \Idno\Core\site()->plugins()->get('SoundCloud')) {
        if (empty(\Idno\Core\site()->session()->currentUser()->soundcloud)) {
            $login_url = $soundcloud->getAuthURL();
        } else {
            $login_url = \Idno\Core\site()->config()->getURL() . 'soundcloud/deauth';
        }
    }

?>
<div class="social">
    <a href="<?= $login_url ?>" class="connect scld <?php

        if (!empty(\Idno\Core\site()->session()->currentUser()->soundcloud)) {
            echo 'connected';
        }

    ?>" target="_top">SoundCloud<?php

            if (!empty(\Idno\Core\site()->session()->currentUser()->soundcloud)) {
                echo ' - connected!';
            }

        ?></a>
    <label class="control-label">Share audio to SoundCloud.</label>
</div>