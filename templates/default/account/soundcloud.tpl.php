<div class="row">

    <div class="span10 offset1">
	            <?=$this->draw('account/menu')?>
        <h1>Soundcloud</h1>

    </div>

</div>
<div class="row">
    <div class="span10 offset1">
        <form action="/account/soundcloud/" class="form-horizontal" method="post">
            <?php
                if (empty(\Idno\Core\site()->session()->currentUser()->soundcloud)) {
            ?>
                    <div class="control-group">
                        <div class="controls">
                            <p>
                                If you have a Soundcloud account, you may connect it here. Public content that you
                                post to this site will be automatically cross-posted to your Soundcloud wall.
                            </p>
                            <?php

                                if (empty($vars['login_url'])) {

                                    if (\Idno\Core\site()->session()->isAdmin()) {

                                        ?>
                                        <p>
                                            You need to set up an API integration with SoundCloud before you connect
                                            your account. <a href="<?=\Idno\Core\site()->config()->getDisplayURL()?>admin/soundcloud/">Click here to get started.</a>
                                        </p>
                                        <?php

                                    } else {

                                        ?>
                                        <p>
                                            SoundCloud isn't available right now. Please try again later.
                                        </p>
                                        <?php

                                    }

                                } else {

                                    ?>
                                    <p>
                                        <a href="<?= $vars['login_url'] ?>" class="btn btn-large btn-success">Click here
                                            to connect SoundCloud to your account</a>
                                    </p>
                                <?php

                                }

                            ?>
                        </div>
                    </div>
                <?php

                } else {

                    ?>
                    <div class="control-group">
                        <div class="controls">
                            <p>
                                Your account is currently connected to Soundcloud. Public content that you post here
                                will be shared with your Soundcloud account.
                            </p>
                            <p>
                                <input type="hidden" name="remove" value="1" />
                                <button type="submit" class="btn btn-primary">Click here to remove Soundcloud from your account.</button>
                            </p>
                        </div>
                    </div>

                <?php

                }
            ?>
            <?= \Idno\Core\site()->actions()->signForm('/account/soundcloud/')?>
        </form>
    </div>
</div>
