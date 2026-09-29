# Changelog

## 0.5.0 - 2026-09-21

- Dashboardkop toont de twee hoogst geprioriteerde snelle moduleacties naast AJAX-verversen.
- Statistiekkaarten, aandachtspunten en agenda-items hebben duidelijkere doorklik- en statussignalen.
- Invoice en Quote leveren aankomende betaal- en offertedeadlines aan de gezamenlijke agenda.
- Moduleoverzicht en secundaire dashboardkolommen zijn responsiever afgestemd op het algemene-overzichtontwerp.
- Herbruikbare icon-, datum- en rechterkolomclasses zijn toegevoegd aan de gedeelde Admin UI-SCSS.

## 0.4.0 - 2026-09-21

- Conventiegebaseerde providerloader toegevoegd voor actieve en toegankelijke modules.
- Facturen en offertes leveren live kerncijfers en de twee primaire dashboardtabellen.
- Klanten, uitgaven en bankieren leveren recente activiteit, snelle acties, aandachtspunten en modulekaarten.
- Dashboardkaarten, modulekaarten en tabelkoppen linken nu door naar hun bronmodule.
- De refreshactie gebruikt Flexgrids declaratieve AJAX-laag en de zichtbare `button-secondary`-variant.
- Provider-, UI-contract- en echte MariaDB-querytests toegevoegd; er zijn geen nieuwe entities of controllerannotations.

## 0.3.0 - 2026-09-21

- Het losse AdminDashboard-paneel verwijderd ten gunste van het gezamenlijke AdminCore-paneel.
- De dashboardcontroller publiceert alleen nog navigatiemetadata voor de link naar het algemene administratieoverzicht.

## 0.2.1 - 2026-09-18

- Gedeelde administratiepresentatie verplaatst naar Flexgrid `Html/Admin`, zodat andere modules dezelfde tokens en classes zonder Dashboard-afhankelijkheid gebruiken.

## 0.2.0 - 2026-09-18

- Dashboardtabellen overgezet op de generieke Flexgrid `TableRenderer`.
- Dashboard-specifieke tabelmarkup en dubbele tabel-SCSS verwijderd.

## 0.1.0 - 2026-09-17

- Eerste Flexgrid-controller, dashboardtegel en algemeen overzicht toegevoegd.
- Centrale Admin UI-tokens en herbruikbare dashboardcomponentclasses toegevoegd.
- AJAX-refresh met stabiele contentcontainer toegevoegd.
- Expliciet providercontract met id-gebaseerde overrides toegevoegd.
- Eerlijke lege toestanden toegevoegd voor nog niet aanwezige businessmodules.
