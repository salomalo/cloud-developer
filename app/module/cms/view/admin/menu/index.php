
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-list"></i> Menu</li>
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

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list fa-fw"></i> All Menu Links</h3>
                <div class="panel-buttons text-right">
                    <div class="btn-group-xs">
                        <a href="/admin/menu/new" class="btn btn-success ajax-link"><i class="fa fa-plus"></i> New Menu</a>
                    </div>
                </div>
            </div>
            <?php if (!empty($menus)): ?>
            <div class="panel-body nopadding">
                <div class="table-responsive">
                    <table class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th style="width:1%"></th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Visibility</th>
                                <th style="width:1%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menus as $row): ?>
                            <tr>
                                <td><?= (!empty($row->icon) ? '<i class="'.$row->icon.'"></i>' : '') ?></td>
                                <td><a href="/admin/menu/edit/<?= $row->id ?>" class="ajax-link"><?= $row->title ?></a></td>
                                <td><?= $row->slug ?></td>
                                <td><?= $visibilityname($row->visibility) ?></td>
                                <td><a href="/admin/menu/delete/<?= $row->id ?>" class="btn btn-xs btn-danger remove-menu"><i class="fa fa-times"></i></a></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
            <div class="panel-body">
                You have not added any menu links.
            </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
$(document).ready(function() {
    load.script('/js/module/menu.js?developer', function(){
        menu.index();
    });
 });
</script>
<?php $f3->set('javascript', $f3->get('javascript').ob_get_clean()) ?>
