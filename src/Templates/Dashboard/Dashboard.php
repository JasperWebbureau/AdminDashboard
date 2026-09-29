<?php
$h = function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};
?>

<section class="admin-page admin-dashboard">
    <div class="admin-page-header">
        <div>
            <p class="admin-page-header__intro">
                <?=t('admin_dashboard_intro', 'Snel overzicht van je administratie, taken en openstaande acties.')?>
            </p>
        </div>

        <div class="admin-page-header__actions admin-dashboard-header-actions">
            <?php foreach (($headerActions ?? []) as $index => $action) { ?>
                <a class="button <?=$index === 0 ? 'button-publish' : 'button-secondary'?>" href="<?=$h($action['href'] ?? '#')?>">
                    <i class="<?=$h($action['icon'] ?? 'fas fa-plus')?>"></i>
                    <?=$h($action['label'] ?? '')?>
                </a>
            <?php } ?>
            <form class="admin-dashboard-refresh" ajax="true" action="<?=$h($refreshAction ?? '')?>" method="post">
                <button class="button button-secondary" type="submit" title="<?=t('admin_dashboard_refresh', 'Dashboard vernieuwen')?>">
                    <i class="fas fa-sync-alt"></i>
                    <span><?=t('admin_dashboard_refresh_short', 'Vernieuwen')?></span>
                </button>
            </form>
        </div>
    </div>

    <div data-admin-dashboard-content>
        <?=$content ?? ''?>
    </div>
</section>
