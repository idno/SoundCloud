<div class="row">

    <div class="col-md-10 col-md-offset-1">
        <?= $this->draw('account/menu') ?>
        <h1>Soundcloud</h1>

    </div>

</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <?php
            if (empty(\Idno\Core\site()->session()->currentUser()->soundcloud)) {
                ?>
                <div class="control-group">
                    <div class="controls-config">
                        <div class="row">
                            <div class="col-md-7">
                                <p>
                                    Easily share audio to Soundcloud.</p>

                                <p>
                                    With Soundcloud connected, you can cross-post audio that you publish publicly on
                                    your site.
                                </p>
                        <div class="social">
                            <p>
                                <a href="<?= $vars['login_url'] ?>" class="connect scld"><i class="fa fa-soundcloud"></i>
 Connect Soundcloud</a>
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
                            <div class="col-md-7">
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
                                    <button type="submit" class="connect scld connected"><i class="fa fa-soundcloud"></i>
 Disconnect Soundcloud</button>
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
                            <div class="col-md-7">
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
                                        class="connect scld connected"><i class="fa fa-soundcloud"></i>
 <?= $account['username'] ?> (Disconnect)
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
                                        <a href="<?= $vars['oauth_url'] ?>" class=""><i class="fa fa-plus"></i> Add another Soundcloud account</a>
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
