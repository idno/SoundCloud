<div class="row">

    <div class="col-md-10 col-md-offset-1">
	            <?=$this->draw('admin/menu')?>
        <h1>Soundcloud configuration</h1>
    </div>

</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <form action="<?=\Idno\Core\site()->config()->getURL()?>admin/soundcloud/" class="form-horizontal" method="post">
            <div class="controls-group">
                <div class="controls-config">
                    <p>
                        To begin using SoundCloud, <a href="http://soundcloud.com/you/apps/new" target="_blank">create a new application in
                            the SoundCloud apps portal</a>.</p>
                    <p>
                        Use <strong><?=\Idno\Core\site()->config()->url?>soundcloud/callback</strong>
                        as the site URL.
                    </p>
                </div>
            </div>
            <div class="controls-group">
	                  <p>
                        Once you've finished, fill in the details below. You can then <a href="<?=\Idno\Core\site()->config()->getURL()?>account/soundcloud/">connect your SoundCloud account</a>.
                    </p>
                <label class="control-label" for="name">Client ID</label>

                    <input type="text" id="name" placeholder="Client ID" class="form-control" name="clientId" value="<?=htmlspecialchars(\Idno\Core\site()->config()->soundcloud['clientId'])?>" >

                <label class="control-label" for="name">Client secret</label>

                    <input type="text" id="name" placeholder="Client secret" class="form-control" name="clientSecret" value="<?=htmlspecialchars(\Idno\Core\site()->config()->soundcloud['clientSecret'])?>" >

            </div>
         <div class="controls-group">
	          <p>
                        After the Soundcloud application is configured, site users must authenticate their Soundcloud account under Settings
                    </p>

          </div> 
            <div class="controls-group">
                <div class="controls-save">
                    <button type="submit" class="btn btn-primary">Save settings</button>
                </div>
            </div>
            <?= \Idno\Core\site()->actions()->signForm('/admin/soundcloud/')?>
        </form>
    </div>
</div>
