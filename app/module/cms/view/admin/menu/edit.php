<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="/admin/menu"><i class="fa fa-list"></i> Menu</a></li>
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

<form class="form-horizontal" method="post" action="/admin/menu/edit/<?= (!empty($form['values']['id']) ? htmlentities($form['values']['id']) : '') ?>">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list fa-fw"></i> Edit Menu</h3>
                </div>
                <div class="panel-body nopadding">
                    <table class="table table-condensed form-table">
                        <tbody>
                            <tr class="form-group<?= (!empty($form['errors']['title']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right col-md-2"><label for="input-title" class="control-label">Title</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <input type="text" class="form-control" id="input-title" name="title" value="<?= (!empty($form['values']['title']) ? htmlentities($form['values']['title']) : '') ?>" placeholder="Enter title... e.g: Blog">
                                    </div>
                                    <?php if (!empty($form['errors']['title'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['title'])): ?><span class="help-block"><?= $form['errors']['title'] ?></span><?php endif ?>
                                </td>
                            </tr>
                            <tr class="form-group<?= (!empty($form['errors']['icon']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right col-md-2"><label for="input-icon" class="control-label">Icon</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <input list="input-icon" class="form-control" name="icon" value="<?= (!empty($form['values']['icon']) ? htmlentities($form['values']['icon']) : '') ?>" placeholder="Enter icon (Font Awesome)... e.g: fa fa-horn">
                                        <datalist id="input-icon"></datalist>
                                    </div>
                                    <?php if (!empty($form['errors']['icon'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['icon'])): ?><span class="help-block"><?= $form['errors']['icon'] ?></span><?php endif ?>
                                </td>
                            </tr>
                            <tr class="form-group<?= (!empty($form['errors']['slug']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right col-md-2"><label for="input-slug" class="control-label">Slug</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <input type="text" class="form-control" id="input-slug" name="slug" value="<?= (!empty($form['values']['slug']) ? htmlentities($form['values']['slug']) : '') ?>" placeholder="Enter slug... e.g: /blog">
                                    </div>
                                    <?php if (!empty($form['errors']['slug'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['slug'])): ?><span class="help-block"><?= $form['errors']['slug'] ?></span><?php endif ?>
                                </td>
                            </tr>
                            <tr class="form-group<?= (!empty($form['errors']['order']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right"><label for="input-order" class="control-label">Order</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <select class="form-control" id="input-order" name="order">
                                            <?php foreach (range(1, 100) as $row): ?>
                                            <option value="<?= $row ?>"<?= ($form['values']['order'] == $row ? ' selected' : '') ?>><?= $row.$helper['getMenuNameByOrder']($row) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <?php if (!empty($form['errors']['order'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['order'])): ?><span class="help-block"><?= $form['errors']['order'] ?></span><?php endif ?>
                                </td>
                            </tr>
                            <tr class="form-group<?= (!empty($form['errors']['visibility']) ? ' has-error has-feedback' : '') ?>">
                                <td class="text-right col-md-2"><label for="input-visibility" class="control-label">Visibility</label></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <select class="form-control" id="input-visibility" name="visibility">
                                            <option value="1"<?= ($form['values']['visibility'] == '1' ? ' selected' : '') ?>>Always</option>
                                            <option value="2"<?= ($form['values']['visibility'] == '2' ? ' selected' : '') ?>>When not signed in</option>
                                            <option value="3"<?= ($form['values']['visibility'] == '3' ? ' selected' : '') ?>>When signed in</option>
                                            <option value="4"<?= ($form['values']['visibility'] == '4' ? ' selected' : '') ?>>When developer</option>
                                        </select>
                                    </div>
                                    <?php if (!empty($form['errors']['visibility'])): ?><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php endif ?>
                                    <?php if (!empty($form['errors']['visibility'])): ?><span class="help-block"><?= $form['errors']['slug'] ?></span><?php endif ?>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="form-group">
                                <td class="text-right"></td>
                                <td>
                                    <div class="input-group col-xs-10">
                                        <button type="submit" class="btn btn-primary ajax_save" data-message="Menu saved." data-goto="/admin/menu">Save</button>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<?php ob_start() ?>
<script>
$(document).ready(function() {
    load.script('/js/module/menu.js?developer', function(){
        menu.edit();
    });
 });
</script>
<?php $f3->set('javascript', $f3->get('javascript').ob_get_clean()) ?>