<?php
$summaryCards = $summary_cards ?? [];
$activities = $activities ?? [];
$quickActions = $quick_actions ?? [];
$attentionItems = $attention ?? [];
$tables = $tables ?? [];
$moduleItems = $modules ?? [];
$agendaItems = $agenda ?? [];
$customPanels = $panels ?? [];

$h = function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};
$tone = function ($value) {
    $allowed = ['info', 'success', 'accent', 'warning', 'danger', 'neutral'];
    return in_array($value, $allowed, true) ? $value : 'neutral';
};
?>

<div class="admin-dashboard-content">
    <?php if ($customPanels !== []) { ?>
        <grid class="admin-dashboard-grid admin-dashboard-grid--custom" data-admin-dashboard-section="custom">
            <?php foreach ($customPanels as $customPanel) { ?>
                <?=$customPanel['html'] ?? ''?>
            <?php } ?>
        </grid>
    <?php } ?>
    <div class="admin-summary-grid" data-admin-dashboard-section="summary">
        <?php foreach ($summaryCards as $card) { ?>
            <?php $cardTone = $tone($card['tone'] ?? 'neutral'); ?>
            <?php $cardHref = trim((string)($card['href'] ?? '')); ?>
            <<?=$cardHref !== '' ? 'a' : 'article'?> class="admin-stat-card<?=$cardHref !== '' ? ' admin-stat-card--link' : ''?>"<?=$cardHref !== '' ? ' href="' . $h($cardHref) . '"' : ''?>>
                <span class="admin-tone-icon is-<?=$h($cardTone)?>">
                    <i class="<?=$h($card['icon'] ?? 'fas fa-chart-simple')?>"></i>
                </span>
                <div class="admin-stat-card__content">
                    <span class="admin-stat-card__label"><?=$h($card['label'] ?? '')?></span>
                    <strong class="admin-stat-card__value">
                        <?=isset($card['value']) ? $h($card['value']) : '&mdash;'?>
                    </strong>
                    <small class="admin-stat-card__meta"><?=$h($card['meta'] ?? '')?></small>
                </div>
                <?php if ($cardHref !== '') { ?><i class="fas fa-chevron-right admin-stat-card__chevron"></i><?php } ?>
            </<?=$cardHref !== '' ? 'a' : 'article'?>>
        <?php } ?>
    </div>

    <grid class="admin-dashboard-grid admin-dashboard-grid--primary">
        <section class="panel admin-panel admin-dashboard-activity" style="--cw:5;--cw-sm:12" data-admin-dashboard-section="activity">
            <div class="panel__header admin-panel__header">
                <h3><i class="fas fa-history"></i> <?=t('admin_dashboard_activity_title', 'Recente activiteit')?></h3>
            </div>
            <div class="panel__body admin-panel__body">
                <?php if ($activities === []) { ?>
                    <div class="admin-empty-state">
                        <i class="fas fa-clock"></i>
                        <p><?=t('admin_dashboard_activity_empty', 'Activiteiten uit aangesloten modules verschijnen hier automatisch.')?></p>
                    </div>
                <?php } else { ?>
                    <div class="admin-list">
                        <?php foreach ($activities as $activity) { ?>
                            <a class="admin-list__item" href="<?=$h($activity['href'] ?? '#')?>">
                                <span class="admin-tone-icon is-<?=$h($tone($activity['tone'] ?? 'info'))?>">
                                    <i class="<?=$h($activity['icon'] ?? 'fas fa-circle')?>"></i>
                                </span>
                                <span class="admin-list__content">
                                    <strong><?=$h($activity['title'] ?? '')?></strong>
                                    <small><?=$h($activity['description'] ?? '')?></small>
                                </span>
                                <time><?=$h($activity['time'] ?? '')?></time>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>

        <section class="panel admin-panel admin-dashboard-actions" style="--cw:3;--cw-sm:6" data-admin-dashboard-section="quick-actions">
            <div class="panel__header admin-panel__header">
                <h3><i class="fas fa-bolt"></i> <?=t('admin_dashboard_actions_title', 'Snelle acties')?></h3>
            </div>
            <div class="panel__body admin-panel__body">
                <?php if ($quickActions === []) { ?>
                    <div class="admin-empty-state admin-empty-state--compact">
                        <i class="fas fa-puzzle-piece"></i>
                        <p><?=t('admin_dashboard_actions_empty', 'Nieuwe acties worden zichtbaar zodra een administratiemodule actief is.')?></p>
                    </div>
                <?php } else { ?>
                    <nav class="admin-action-list" aria-label="<?=t('admin_dashboard_actions_label', 'Snelle acties')?>">
                        <?php foreach ($quickActions as $action) { ?>
                            <a href="<?=$h($action['href'] ?? '#')?>">
                                <span class="admin-action-list__icon is-<?=$h($tone($action['tone'] ?? 'neutral'))?>"><i class="<?=$h($action['icon'] ?? 'fas fa-arrow-right')?>"></i></span>
                                <span><?=$h($action['label'] ?? '')?></span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php } ?>
                    </nav>
                <?php } ?>
            </div>
        </section>

        <section class="panel admin-panel admin-dashboard-attention" style="--cw:4;--cw-sm:6" data-admin-dashboard-section="attention">
            <div class="panel__header admin-panel__header">
                <h3><i class="fas fa-exclamation-triangle"></i> <?=t('admin_dashboard_attention_title', 'Aandacht nodig')?></h3>
            </div>
            <div class="panel__body admin-panel__body">
                <?php if ($attentionItems === []) { ?>
                    <div class="admin-empty-state">
                        <i class="fas fa-circle-check"></i>
                        <p><?=t('admin_dashboard_attention_empty', 'Er zijn momenteel geen aandachtspunten aangeleverd.')?></p>
                    </div>
                <?php } else { ?>
                    <div class="admin-list">
                        <?php foreach ($attentionItems as $item) { ?>
                            <a class="admin-list__item" href="<?=$h($item['href'] ?? '#')?>">
                                <span class="admin-tone-icon is-<?=$h($tone($item['tone'] ?? 'warning'))?>">
                                    <i class="<?=$h($item['icon'] ?? 'fas fa-exclamation-triangle')?>"></i>
                                </span>
                                <span class="admin-list__content">
                                    <strong><?=$h($item['title'] ?? '')?></strong>
                                    <small><?=$h($item['description'] ?? '')?></small>
                                </span>
                                <span class="admin-list__aside">
                                    <?php if (!empty($item['badge'])) { ?><span class="admin-status is-<?=$h($tone($item['badge_tone'] ?? $item['tone'] ?? 'warning'))?>"><?=$h($item['badge'])?></span><?php } ?>
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>
    </grid>

    <grid class="admin-dashboard-grid admin-dashboard-grid--tables" data-admin-dashboard-section="tables">
        <?php foreach ($tables as $table) { ?>
            <section class="panel admin-panel admin-dashboard-table-panel" style="--cw:6;--cw-sm:12">
                <div class="panel__header admin-panel__header">
                    <h3><i class="<?=$h($table['icon'] ?? 'fas fa-table')?>"></i> <?=$h($table['title'] ?? '')?></h3>
                    <?php if (!empty($table['href'])) { ?>
                        <a class="admin-panel__link" href="<?=$h($table['href'])?>"><?=$h($table['link_label'] ?? t('admin_dashboard_view_all', 'Bekijk alles'))?> <i class="fas fa-chevron-right"></i></a>
                    <?php } ?>
                </div>
                <div class="panel__body admin-panel__body admin-panel__body--flush">
                    <?php
                    $tableColumns = [];
                    foreach (($table['columns'] ?? []) as $columnIndex => $columnLabel) {
                        $tableColumns[] = [
                            'key' => 'column_' . $columnIndex,
                            'label' => (string)$columnLabel,
                        ];
                    }

                    $tableRows = [];
                    foreach (($table['rows'] ?? []) as $rowIndex => $row) {
                        $cells = [];
                        foreach (($row['cells'] ?? []) as $cellIndex => $cell) {
                            $cells['column_' . $cellIndex] = $cell;
                        }
                        $tableRows[] = [
                            'id' => $row['id'] ?? $rowIndex,
                            'url' => $row['url'] ?? '',
                            'state' => $row['state'] ?? '',
                            'cells' => $cells,
                            'actions' => $row['actions'] ?? [],
                        ];
                    }

                    $tableRenderer = new \Flexgrid\Html\Table\TableRenderer([
                        'id' => (string)($table['id'] ?? 'dashboard-table'),
                        'label' => (string)($table['title'] ?? ''),
                        'compact' => true,
                        'columns' => $tableColumns,
                        'rows' => $tableRows,
                        'empty' => [
                            'title' => t('admin_dashboard_table_empty_title', 'Nog geen gegevens'),
                            'message' => (string)($table['empty'] ?? t('admin_dashboard_table_empty', 'Nog geen gegevens beschikbaar.')),
                            'icon' => 'fas fa-table',
                        ],
                    ]);
                    echo $tableRenderer;
                    ?>
                </div>
            </section>
        <?php } ?>
    </grid>

    <grid class="admin-dashboard-grid admin-dashboard-grid--secondary">
        <section class="panel admin-panel admin-dashboard-modules" style="--cw:8;--cw-sm:12" data-admin-dashboard-section="modules">
            <div class="panel__header admin-panel__header">
                <h3><i class="fas fa-th-large"></i> <?=t('admin_dashboard_modules_title', 'Moduleoverzicht')?></h3>
            </div>
            <div class="panel__body admin-panel__body">
                <div class="admin-module-grid">
                    <?php foreach ($moduleItems as $module) { ?>
                        <?php $moduleTone = $tone($module['tone'] ?? 'neutral'); ?>
                        <?php $moduleHref = trim((string)($module['href'] ?? '')); ?>
                        <<?=$moduleHref !== '' ? 'a' : 'article'?> class="admin-module-card<?=$moduleHref !== '' ? ' admin-module-card--link' : ''?>"<?=$moduleHref !== '' ? ' href="' . $h($moduleHref) . '"' : ''?>>
                            <span class="admin-tone-icon is-<?=$h($moduleTone)?>">
                                <i class="<?=$h($module['icon'] ?? 'fas fa-cube')?>"></i>
                            </span>
                            <span class="admin-module-card__content">
                                <strong><?=$h($module['title'] ?? '')?></strong>
                                <small><?=$h($module['description'] ?? '')?></small>
                            </span>
                            <span class="admin-status is-<?=$h($moduleTone)?>"><?=$h($module['status'] ?? '')?></span>
                        </<?=$moduleHref !== '' ? 'a' : 'article'?>>
                    <?php } ?>
                </div>
            </div>
        </section>

        <section class="panel admin-panel admin-dashboard-agenda" style="--cw:4;--cw-sm:12" data-admin-dashboard-section="agenda">
            <div class="panel__header admin-panel__header">
                <h3><i class="fas fa-calendar-alt"></i> <?=t('admin_dashboard_agenda_title', 'Agenda / komende momenten')?></h3>
            </div>
            <div class="panel__body admin-panel__body">
                <?php if ($agendaItems === []) { ?>
                    <div class="admin-empty-state admin-empty-state--compact">
                        <i class="fas fa-calendar-check"></i>
                        <p><?=t('admin_dashboard_agenda_empty', 'Er zijn nog geen geplande administratiemomenten.')?></p>
                    </div>
                <?php } else { ?>
                    <div class="admin-list">
                        <?php foreach ($agendaItems as $item) { ?>
                            <a class="admin-list__item" href="<?=$h($item['href'] ?? '#')?>">
                                <time class="admin-list__date" datetime="<?=$h($item['date_iso'] ?? '')?>">
                                    <?php if (!empty($item['day'])) { ?><strong><?=$h($item['day'])?></strong><small><?=$h($item['month'] ?? '')?></small><?php } else { ?><?=$h($item['date'] ?? '')?><?php } ?>
                                </time>
                                <span class="admin-list__content">
                                    <strong><?=$h($item['title'] ?? '')?></strong>
                                    <small><?=$h($item['description'] ?? '')?></small>
                                </span>
                                <span class="admin-list__aside"><time><?=$h($item['time'] ?? '')?></time><i class="fas fa-chevron-right"></i></span>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>
    </grid>

    <p class="admin-dashboard-updated">
        <?=t('admin_dashboard_updated', 'Laatst opgebouwd')?>: <?=$h($updated_at ?? '')?>
    </p>
</div>
