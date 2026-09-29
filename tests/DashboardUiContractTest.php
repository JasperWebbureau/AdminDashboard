<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$moduleRoot = dirname(__DIR__);
$dashboard = file_get_contents($moduleRoot . '/src/Templates/Dashboard/Dashboard.php');
$content = file_get_contents($moduleRoot . '/src/Templates/Dashboard/Content.php');
$styles = file_get_contents($moduleRoot . '/src/Templates/Dashboard/Css/Dashboard.scss');

adminDashboardAssert(strpos($dashboard, 'ajax="true"') !== false, 'Dashboardverversing moet Flexgrids declaratieve AJAX-laag gebruiken.');
adminDashboardAssert(strpos($dashboard, 'button-secondary') !== false && strpos($dashboard, 'button-outline') === false, 'Dashboardacties gebruiken geen slecht zichtbare outlineknop.');
adminDashboardAssert(strpos($dashboard, 'headerActions') !== false && strpos($dashboard, 'button-publish') !== false, 'Dashboardkop moet de belangrijkste moduleacties zichtbaar maken.');
adminDashboardAssert(strpos($content, "['href']") !== false, 'Dashboardkaarten en tabellen moeten naar hun bronmodule kunnen navigeren.');
adminDashboardAssert(strpos($content, 'TableRenderer') !== false, 'Dashboardtabellen moeten de gedeelde TableRenderer gebruiken.');
adminDashboardAssert(strpos($content, 'admin-dashboard-grid--custom') !== false
    && strpos($content, "['html']") !== false,
    'Geïnjecteerde projectpanelen moeten als directe kinderen van een Flexgrid-grid worden gerenderd.');
adminDashboardAssert(strpos($content, 'admin-list__aside') !== false && strpos($content, 'admin-stat-card__chevron') !== false, 'Dashboarditems moeten duidelijke status- en doorkliksignalen tonen.');
adminDashboardAssert(strpos($styles, 'var(--admin-color-primary)') !== false, 'Dashboardstyling moet gedeelde kleurvariabelen gebruiken.');
adminDashboardAssert(strpos($styles, '#') === false, 'Dashboardstyling mag geen losse kleurwaarden toevoegen.');

foreach (['AdminCustomer', 'AdminQuote', 'AdminInvoice', 'AdminExpense', 'AdminBanking'] as $module) {
    $provider = dirname($moduleRoot) . '/' . $module . '/src/Integration/Dashboard/DashboardProvider.php';
    adminDashboardAssert(is_file($provider), $module . ' moet via de Dashboard-providerconventie bijdragen.');
    $providerCode = file_get_contents($provider);
    adminDashboardAssert(strpos($providerCode, 'DashboardProviderInterface') !== false, $module . ' moet het consumer-owned dashboardcontract implementeren.');
    adminDashboardAssert(stripos($providerCode, 'jquery') === false && stripos($providerCode, 'fetch(') === false, $module . ' mag geen eigen AJAX-stack introduceren.');
}

echo "AdminDashboard UI-contract tests passed.\n";
