<?php

    if ($soundcloud = \Idno\Core\site()->plugins()->get('SoundCloud')) {
        $login_url = $soundcloud->getAuthURL();
    }

?>
<div class="social">
    <a href="<?= $login_url ?>" class="connect scld <?php

        if (!empty(\Idno\Core\site()->session()->currentUser()->soundcloud)) {
            echo 'connected';
        }

    ?>">SoundCloud<?php

            if (!empty(\Idno\Core\site()->session()->currentUser()->soundcloud)) {
                echo ' - connected!';
            }

        ?></a>
    <label class="control-label">Share audio to SoundCloud.</label>
</div>