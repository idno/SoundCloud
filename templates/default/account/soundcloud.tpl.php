<div class="row">

    <div class="span10 offset1">
        <?= $this->draw('account/menu') ?>
        <h1>Soundcloud</h1>

    </div>

</div>
<div class="row">
    <div class="span10 offset1">
        <?php
            if (empty(\Idno\Core\site()->session()->currentUser()->soundcloud)) {
                ?>
                <div class="control-group">
                    <div class="controls-config">
                        <div class="row">
                            <div class="span6">
                                <p>
                                    Easily share audio to Soundcloud.</p>

                                <p>
                                    With Soundcloud connected, you can cross-post audio that you publish publicly on
                                    your site.
                                </p>
                        <div class="social">
                            <p>
                                <a href="<?= $vars['login_url'] ?>" class="connect scld">Connect Soundcloud</a>
                            </p>
                        </div>
                        
                    </div>
                </div>
                    </div>
                </div>
            <?php

            } else if (!\Idno\Core\site()->config()->multipleSyndicationAccounts()) {

                ?>
                <div class="control-group">
                    <div class="controls-config">
                        <div class="row">
                            <div class="span6">
                                <p>
                                    Your account is currently connected to Soundcloud. Public content that you post
                                    here
                                    will be shared with your Soundcloud account.
                                </p>
                        <div class="social">
                            <form action="<?= \Idno\Core\site()->config()->getDisplayURL() ?>soundcloud/deauth"
                                  class="form-horizontal" method="post">
                                <p>
                                    <input type="hidden" name="remove" value="1"/>
                                    <button type="submit" class="connect scld connected">Disconnect Soundcloud</button>
                                    <?= \Idno\Core\site()->actions()->signForm('/account/soundcloud/') ?>
                                </p>
                            </form>
                        </div>
                        
                    </div>
                </div>
                    </div>
                </div>

            <?php

            } else {

                if ($accounts = \Idno\Core\site()->syndication()->getServiceAccounts('soundcloud')) {

                    foreach ($accounts as $account) {

                        ?>
                  <div class="control-group">
                    <div class="controls-config">
                        <div class="row">
                            <div class="span6">
                                <p>
                                    Your account is currently connected to Soundcloud. Public content that you post
                                    here
                                    will be shared with your Soundcloud account.
                                </p>
                                
                       <div class="social">       
                        <form action="<?= \Idno\Core\site()->config()->getDisplayURL() ?>soundcloud/deauth"
                              class="form-horizontal" method="post">
                            <p>
                                <input type="hidden" name="remove" value="<?= $account['username'] ?>"/>
                                <button type="submit"
                                        class="connect scld connected"><?= $account['username'] ?> (Disconnect)
                                </button>
                                <?= \Idno\Core\site()->actions()->signForm('/account/soundcloud/') ?>
                            </p>
                        </form>
                       </div>
                    <?php

                    }

                }

                ?>
                <p>
                    <a href="<?= $vars['login_url'] ?>" class=""><icon class="icon-plus"></icon> Add another SoundCloud account</a>
                </p>
                            </div>
                        </div>
                    </div>
                  </div>

            <?php

            }
        ?>
    </div>
</div>
