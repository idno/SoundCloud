<div class="row">

    <div class="span10 offset1">
        <h1>Soundcloud</h1>
        <?=$this->draw('admin/menu')?>
    </div>

</div>
<div class="row">
    <div class="span10 offset1">
        <form action="<?=\Idno\Core\site()->config()->getURL()?>admin/soundcloud/" class="form-horizontal" method="post">
            <div class="control-group">
                <div class="controls">
                    <p>
                        To begin using SoundCloud, <a href="http://soundcloud.com/you/apps/new" target="_blank">create a new application in
                            the SoundCloud apps portal</a>.</p>
                    <p>
                        Use <strong><?=\Idno\Core\site()->config()->url?>soundcloud/callback</strong>
                        as the site URL.
                    </p>
                    <p>
                        Once you've finished, fill in the details below. You can then <a href="<?=\Idno\Core\site()->config()->getURL()?>settings/soundcloud/">connect your SoundCloud account</a>.
                    </p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">Client ID</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="Client ID" class="span4" name="clientId" value="<?=htmlspecialchars(\Idno\Core\site()->config()->soundcloud['clientId'])?>" >
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">Client secret</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="Client secret" class="span4" name="clientSecret" value="<?=htmlspecialchars(\Idno\Core\site()->config()->soundcloud['clientSecret'])?>" >
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
            <?= \Idno\Core\site()->actions()->signForm('/admin/soundcloud/')?>
        </form>
    </div>
</div>
