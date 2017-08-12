<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Settings
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-cogs"></i> Settings</li>
        </ol>
    </div>
</div>

<?php if (!empty($form['errors']['global'])): ?>
<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert">&times;</a>
    <?= $form['errors']['global'] ?>
</div>
<?php endif ?>
<?php if (!empty($form['errors']['success'])): ?>
    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <?= $form['errors']['success'] ?>
    </div>
<?php endif ?>

<form class="form-horizontal" method="post">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-cogs fa-fw"></i> Site Settings</h3>
                </div>
                <div class="panel-body nopadding">
                    <table class="table table-condensed form-table">
                        <tbody>
                            <tr class="form-group<?= (!empty($form['errors']['sitename']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right col-md-2"><label for="input-title" class="control-label">Site Name</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <input type="text" class="form-control" id="input-sitename" name="sitename" value="<?= (!empty($helper['extractValue']('sitename')) ? htmlentities($helper['extractValue']('sitename')) : '') ?>" placeholder="title...">
                                    </div>
                                    <?php if (!empty($form['errors']['sitename'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['sitename'])): ?><span class="help-block"><?= $form['errors']['sitename'] ?></span><?php endif ?>
                                </td>
                            </tr>
                            <tr class="form-group<?= (!empty($form['errors']['autogenerate']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right col-md-2"><label for="input-autogenerate" class="control-label">Auto-Generate Pages</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <select class="form-control" id="input-autogenerate" name="autogenerate">
                                            <?php $current = $helper['extractValue']('autogenerate'); ?>
                                            <option value="1"<?= ($current  == '1' ? ' selected' : '') ?>>Yes</option>
                                            <option value="0"<?= ($current  == '0' ? ' selected' : '') ?>>No</option>
                                        </select>
                                        <?php if (!empty($form['errors']['autogenerate'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                        <?php if (!empty($form['errors']['autogenerate'])): ?>
                                            <span class="help-block"><?= $form['errors']['autogenerate'] ?></span>
                                        <?php else: ?>
                                            <?php if ($current  == '1'): ?>
                                            <span class="help-block">Visit any url to automatically generate your pages.</span>
                                            <?php else: ?>
                                            <span class="help-block">Pages are not generated, instead users will be shown a 404.</span>
                                            <?php endif ?>
                                        <?php endif ?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="form-group">
                                <td class="text-right"></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-code fa-fw"></i> Composer</h3>
                </div>
                <div class="panel-body nopadding">
                    <?php $current = file_get_contents('./composer.json'); ?>
                    <textarea class="form-control form-textarea" rows="10" id="input-composer" name="composer"><?= $current ?></textarea>
                    <div id="composer" style="position: relative;height: 380px;width: 100%"></div>
                    <?php if (!empty($form['errors']['composer'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                    <?php if (!empty($form['errors']['composer'])): ?><span class="help-block"><?= $form['errors']['composer'] ?></span><?php endif ?>
                </div>
                <div class="panel-footer nopadding">
                    <?php if (!empty($form['values']['composer_result'])): ?>
                    <h5 style="padding-left:7px">Composer Task Output</h5>
                    <pre style="padding:7px 10px;font-size:90%"><?= $form['values']['composer_result'] ?></pre>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $backups = array_diff(scandir('backups/'), array('..', '.')) ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-hdd-o fa-fw"></i> Database Backups</h3>
                <div class="panel-buttons text-right">
                    <div class="btn-group-xs">
                        <a href="/admin/settings/backups/create" class="btn btn-success ajax-link"><i class="fa fa-plus"></i> New Backup</a>
                    </div>
                </div>
            </div>
            <div class="panel-body nopadding">
                <div class="table-responsive">
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Created</th>
                                <th>Size</th>
                                <th style="width:1%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($backups as $row): ?>
                            <tr>
                                <td><?= $row ?></td>
                                <td><?= date_create('@'.filemtime('backups/'.$row))->format('F jS Y, g:ia') ?></td>
                                <td><?= \utilphp\util::size_format(filesize('backups/'.$row), 2 ); ?></td>
                                <td>
                                    <div class="btn-group" style="display:flex">
                                        <a title="Restore" href="/admin/settings/backups/restore?file=<?= base64_encode($row) ?>" class="btn btn-xs btn-primary"><i class="fa fa-reply"></i></a>
                                        <a title="Remove" href="/admin/settings/backups/remove?file=<?= base64_encode($row) ?>" class="btn btn-xs btn-danger remove-backup"><i class="fa fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    $(document).ready(function() {
        
        $(document).on('click', '.remove-backup', function(e){
            e.preventDefault();
            
            var elm = $(this);
            var url = $(this).attr('href');
            $.ajax({
                type: "GET",
                url: url,
                success: function(data) {
                    elm.closest('tr').remove();
                }
            });
        });
        
        var textarea = $('textarea[name="composer"]').hide(),
            editor = ace.edit("composer"),
            editorSession = editor.getSession();

        editor.setTheme("ace/theme/eclipse");
        editor.setOptions({
            minLines: 20,
            maxLines: Infinity
        });

        editorSession.setUseWorker(false);
        editorSession.setMode("ace/mode/json");
        editorSession.setValue(textarea.val());
        editorSession.on('change', function() {
            textarea.val(editorSession.getValue());
        });

        $(window).bind('keydown', function(event) {
            if (event.ctrlKey || event.metaKey) {
                switch (String.fromCharCode(event.which).toLowerCase()) {
                    case 's':
                        event.preventDefault();
                        $('[type="submit"]').trigger('click');
                    break;
                }
            }
        });
        
        // load.script('/js/module/tasks.js', function() {
        //     nodes.init();
        // });
    });
</script>
<?php $f3->set('javascript', $f3->get('javascript').ob_get_clean()) ?>
