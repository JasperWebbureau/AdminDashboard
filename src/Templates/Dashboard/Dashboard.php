<section class="admin-page admin-dashboard">
    <div class="admin-page-header">
        <div>
            <p class="admin-page-header__intro">
                <?=t('admin_dashboard_intro', 'Snel overzicht van je administratie, taken en openstaande acties.')?>
            </p>
        </div>

    </div>

    <div data-admin-dashboard-content>
        <?=$content ?? ''?>
    </div>
</section>
