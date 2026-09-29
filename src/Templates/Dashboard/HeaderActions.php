<?php
$h = static function ($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
?>
<div class="admin-page-header__actions admin-dashboard-header-actions">
    <?php foreach (($headerActions ?? []) as $index => $action) { ?>
        <a class="button <?=$index === 0 ? 'button-publish' : 'button-secondary'?>" href="<?=$h($action['href'] ?? '#')?>">
            <i class="<?=$h($action['icon'] ?? 'fas fa-plus')?>"></i>
            <?=$h($action['label'] ?? '')?>
        </a>
    <?php } ?>
    <form class="admin-dashboard-refresh" ajax="true" action="<?=$h($refreshAction ?? '')?>" method="post">
        <button class="button button-secondary" type="submit" style="--cw:12" title="<?=t('admin_dashboard_refresh', 'Dashboard vernieuwen')?>">
            <i class="fas fa-sync-alt"></i>
            <span><?=t('admin_dashboard_refresh_short', 'Vernieuwen')?></span>
        </button>
    </form>
</div>
