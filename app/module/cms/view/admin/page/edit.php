<?php
$formStyle = [
    'label' => '',
    'input' => ''
];
?>

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="/admin/page"><i class="fa fa-file-o"></i> Pages</a></li>
            <li class="active"><i class="fa fa-pencil"></i> Edit</li>
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

<form class="form-horizontal" method="post" action="/admin/page/edit/<?= (!empty($form['values']['id']) ? htmlentities($form['values']['id']) : '') ?>">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-file-o fa-fw"></i> Page Details</h3>
                </div>
                <div class="panel-body nopadding">
                    <table class="table table-condensed form-table">
                        <tbody>
                            <tr class="form-group<?= (!empty($form['errors']['title']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right col-md-2"><label for="input-title" class="control-label">Title</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <input type="text" class="form-control" id="input-title" name="title" value="<?= (!empty($form['values']['title']) ? htmlentities($form['values']['title']) : '') ?>" placeholder="Enter page title... e.g: My Page">
                                    </div>
                                    <?php if (!empty($form['errors']['title'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['title'])): ?><span class="help-block"><?= $form['errors']['title'] ?></span><?php endif ?>
                                </td>
                            </tr>
                            <tr class="form-group<?= (!empty($form['errors']['slug']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right"><label for="input-slug" class="control-label">Slug</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <input type="text" class="form-control" id="input-slug" name="slug" value="<?= (!empty($form['values']['slug']) ? htmlentities($form['values']['slug']) : '') ?>" placeholder="Enter page slug... e.g: /my-page">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" href="javascript:;" data-type="popup" data-url="<?= (!empty($form['values']['slug']) ? htmlentities($form['values']['slug']) : '') ?>" data-name="<?= (!empty($form['values']['title']) ? htmlentities($form['values']['title']) : '') ?>"><i class="fa fa-external-link"></i> Open</button>
                                        </span>
                                    </div>
                                    <?php if (!empty($form['errors']['slug'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['slug'])): ?><span class="help-block"><?= $form['errors']['slug'] ?></span><?php endif ?>
                                </td>
                            </tr>
                            <tr class="form-group<?= (!empty($form['errors']['module_id']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right"><label for="input-module_id" class="control-label">Module</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <select class="form-control" name="module_id" id="input-module_id">
                                            <?php foreach ($modules as $row): ?>
                                            <option value="<?= $row->id ?>"<?= ($form['values']['module_id'] == $row->id ? ' selected' : '') ?>><?= $row->name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="input-group-btn">
                                            <a href="/admin/module/view/<?= (int) $form['values']['module_id'] ?>" class="btn btn-default"><i class="fa fa-pencil"></i> Edit</a>
                                        </span>
                                    </div>
                                    <?php if (!empty($form['errors']['module_id'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['module_id'])): ?><span class="help-block"><?= $form['errors']['module_id'] ?></span><?php endif ?>
                                </td>
                            </tr>
                            <tr class="form-group<?= (!empty($form['errors']['visibility']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right"><label for="input-visibility" class="control-label">Visibility</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <select class="form-control" id="input-visibility" name="visibility">
                                            <option value="1"<?= ($form['values']['visibility'] == '1' ? ' selected' : '') ?>>Always</option>
                                            <option value="2"<?= ($form['values']['visibility'] == '2' ? ' selected' : '') ?>>When not signed in</option>
                                            <option value="3"<?= ($form['values']['visibility'] == '3' ? ' selected' : '') ?>>When signed in</option>
                                            <option value="4"<?= ($form['values']['visibility'] == '4' ? ' selected' : '') ?>>When developer</option>
                                            <option value="5"<?= ($form['values']['visibility'] == '5' ? ' selected' : '') ?>>Disabled</option>
                                        </select>
                                    </div>
                                    <?php if (!empty($form['errors']['visibility'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['visibility'])): ?><span class="help-block"><?= $form['errors']['visibility'] ?></span><?php endif ?>
                                </td>
                            </tr>
                            <tr class="form-group<?= (!empty($form['errors']['template_id']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right"><label for="input-template_id" class="control-label">Template</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <select class="form-control" name="template_id" id="input-template_id">
                                            <?php foreach ($templates as $row): ?>
                                            <option value="<?= $row->id ?>"<?= ($form['values']['template_id'] == $row->id ? ' selected' : '') ?>><?= $row->name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="input-group-btn">
                                            <a href="/admin/template/edit/<?= (int) $form['values']['template_id'] ?>" class="btn btn-default"><i class="fa fa-pencil"></i> Edit</a>
                                        </span>
                                    </div>
                                    <?php if (!empty($form['errors']['template_id'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['template_id'])): ?><span class="help-block"><?= $form['errors']['template_id'] ?></span><?php endif ?>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="form-group">
                                <td class="text-right"></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <a href="#" role="button" class="btn btn-link btn-xs label-btn hidden" id="page-saved" aria-disabled="true"><span class="text-success">File saved!</span></a>
                                        <button type="submit" class="btn btn-primary save-page ajax_save" data-message="Page saved.">Save</button> <span class="text-muted" style="margin-left:15px"><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Tip: You can also use Ctrl-s to save your changes.</span>
                                    </div>
                                    <?php if (!empty($form['errors']['template_id'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['template_id'])): ?><span class="help-block"><?= $form['errors']['template_id'] ?></span><?php endif ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="timeline">
        <div class="line text-muted"></div>
        <?php if (!empty($objects)): ?>
        <article class="panel panel-default">
            <div class="panel-heading icon">
                <i class="fa fa-angle-down"></i>
            </div>
            <div class="panel-heading">
                <h2 class="panel-title">
                    Load Objects (<?= count($objects) ?>)
                    <div class="panel-buttons text-right">
                        <div class="btn-group-xs">
                            <button type="button" class="btn btn-default toggle-objects-table"><i class="fa fa-eye"></i> Show Objects</button>
                            <a href="/admin/objects/new" class="btn btn-xs btn-primary ajax-link"><i class="fa fa-plus"></i> New Object</a>
                        </div>
                    </div>
                </h2>
            </div>
            <div class="panel-body nopadding hidden objects-table">
                <div class="table-responsive">
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Lines</th>
                                <th>Priority</th>
                                <th style="width:1%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($objects as $row): ?>
                            <tr>
                                <td><?= $row->name ?></td>
                                <td><?= (int) $row->line_count ?></td>
                                <td><?= (int) $row->priority ?></td>
                                <td><a href="/admin/objects/edit/<?= $row->id ?>" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Edit Object</a></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
        <?php endif ?>
        <?php $beforeload = $form['values']->module->beforeload; if (!empty($beforeload)): ?>
        <article class="panel panel-default">
            <div class="panel-heading icon">
                <i class="fa fa-angle-down"></i>
            </div>
            <div class="panel-heading">
                <h2 class="panel-title">
                    Module Before Load
                    <div class="panel-buttons text-right">
                        <div class="btn-group-xs">
                            <button type="button" class="btn btn-default show-code"><i class="fa fa-eye"></i> Show Code</button>
                            <a href="/admin/module/view/<?= $form['values']->module->id ?>" class="btn btn-primary ajax-link"><i class="fa fa-pencil"></i> Edit Module</a>
                        </div>
                    </div>
                </h2>
            </div>
            <div class="panel-body nopadding hidden modulebeforeload-code">
                <textarea class="form-control form-textarea" rows="10" id="modulebeforeload" name="modulebeforeload"><?= $form['values']->module->beforeload ?></textarea>
                <div id="modulebeforeload" style="position: relative;width: 100%"></div>
            </div>
        </article>
        <?php endif ?>
        <article class="panel panel-default">
            <div class="panel-heading icon">
                <i class="fa fa-angle-down"></i>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-code fa-fw"></i> Before Load
                <div class="btn-group pull-right">
                    <?php $snippets = $getsnippets('beforeload'); if (!empty($snippets)): ?>
                    <a href="#" role="button" class="btn btn-link btn-xs label-btn" aria-disabled="true">Snippets:</a>
                    <?php foreach ($snippets as $row): if ($row->type != 'beforeload') {
    continue;
} ?>
                    <button type="button" data-id="<?= $row->id ?>" data-type="<?= $row->type ?>" class="btn btn-xs btn-default fetch-snippet"><?= $row->title ?></button>
                    <?php endforeach ?>
                    <?php endif ?>
                </div>
                </h3>
            </div>
            <div class="panel-body nopadding">
                <div class="">
                    <textarea class="form-control form-textarea" rows="10" id="input-beforeload" name="beforeload"><?= (!empty($form['values']['beforeload']) ? $form['values']['beforeload'] : '') ?></textarea>
                    <div id="beforeload" style="position: relative;height: 380px;width: 100%"></div>
                    <?php if (!empty($form['errors']['beforeload'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                    <?php if (!empty($form['errors']['beforeload'])): ?><span class="help-block"><?= $form['errors']['beforeload'] ?></span><?php endif ?>
                </div>
            </div>
        </article>
        <article class="panel panel-default">
            <div class="panel-heading icon">
                <i class="fa fa-angle-down"></i>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-code fa-fw"></i> Body
                <div class="btn-group pull-right">
                    <?php $snippets = $getsnippets('body'); if (!empty($snippets)): ?>
                    <a href="#" role="button" class="btn btn-link btn-xs label-btn" aria-disabled="true">Snippets:</a>
                    <?php foreach ($snippets as $row): if ($row->type != 'body') {
    continue;
} ?>
                    <button type="button" data-id="<?= $row->id ?>" data-type="<?= $row->type ?>" class="btn btn-xs btn-default fetch-snippet"><?= $row->title ?></button>
                    <?php endforeach ?>
                    <?php endif ?>
                </div>
                </h3>
            </div>
            <div class="panel-body nopadding">
                <div class="">
                    <textarea class="form-control form-textarea" rows="10" id="input-body" name="body"><?= (!empty($form['values']['body']) ? $form['values']['body'] : '') ?></textarea>
                    <div id="body" style="position: relative;height: 380px;width: 100%"></div>
                    <?php if (!empty($form['errors']['body'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                    <?php if (!empty($form['errors']['body'])): ?><span class="help-block"><?= $form['errors']['body'] ?></span><?php endif ?>
                </div>
            </div>
        </article>
        <article class="panel panel-default">
            <div class="panel-heading icon">
                <i class="fa fa-angle-down"></i>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-code fa-fw"></i> CSS
                <div class="btn-group pull-right">
                    <?php $snippets = $getsnippets('css'); if (!empty($snippets)): ?>
                    <a href="#" role="button" class="btn btn-link btn-xs label-btn" aria-disabled="true">Snippets:</a>
                    <?php foreach ($snippets as $row): if ($row->type != 'css') {
    continue;
} ?>
                    <button type="button" data-id="<?= $row->id ?>" data-type="<?= $row->type ?>" class="btn btn-xs btn-default fetch-snippet"><?= $row->title ?></button>
                    <?php endforeach ?>
                    <?php endif ?>
                </div>
                </h3>
            </div>
            <div class="panel-body nopadding">
                <div class="">
                    <textarea class="form-control form-textarea" rows="10" id="input-css" name="css"><?= (!empty($form['values']['css']) ? $form['values']['css'] : '') ?></textarea>
                    <div id="css" style="position: relative;height: 380px;width: 100%"></div>
                    <?php if (!empty($form['errors']['css'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                    <?php if (!empty($form['errors']['css'])): ?><span class="help-block"><?= $form['errors']['css'] ?></span><?php endif ?>
                </div>
            </div>
        </article>
        <article class="panel panel-default">
            <div class="panel-heading icon">
                <i class="fa fa-angle-down"></i>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-code fa-fw"></i> Javascript
                <div class="btn-group pull-right">
                    <?php $snippets = $getsnippets('javascript'); if (!empty($snippets)): ?>
                    <a href="#" role="button" class="btn btn-link btn-xs label-btn" aria-disabled="true">Snippets:</a>
                    <?php foreach ($snippets as $row): if ($row->type != 'javascript') {
    continue;
} ?>
                    <button type="button" data-id="<?= $row->id ?>" data-type="<?= $row->type ?>" class="btn btn-xs btn-default fetch-snippet"><?= $row->title ?></button>
                    <?php endforeach ?>
                    <?php endif ?>
                </div>
                </h3>
            </div>
            <div class="panel-body nopadding">
                <div class="">
                    <textarea class="form-control form-textarea" rows="10" id="input-javascript" name="javascript"><?= (!empty($form['values']['javascript']) ? $form['values']['javascript'] : '') ?></textarea>
                    <div id="javascript" style="position: relative;height: 380px;width: 100%"></div>
                    <?php if (!empty($form['errors']['javascript'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                    <?php if (!empty($form['errors']['javascript'])): ?><span class="help-block"><?= $form['errors']['javascript'] ?></span><?php endif ?>
                </div>
            </div>
        </article>
    </div>
</form>

<?php ob_start() ?>
<script>
$(document).ready(function() {
    load.script('/js/module/page.js?developer', function(){
        page.edit();
    });
 });
</script>
<?php $f3->set('javascript', $f3->get('javascript').ob_get_clean()) ?>
