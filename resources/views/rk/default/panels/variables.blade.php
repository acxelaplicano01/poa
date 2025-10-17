@php
    $footerQuery = rk_navigation()
        ->newQuery()
        ->loadContexts(['footerDashboard'])
        ->filterForCurrentUser();

    $footerItems = $footerQuery->get();

    $configNode = $footerQuery->getSubBranch('settings_group')->first();

    $headerQuery = rk_navigation()
        ->newQuery()
        ->loadContexts(['headerDashboard'])
        ->filterForCurrentUser();

    $headerItems = $headerQuery->get();

    $fullQuery = rk_navigation()
        ->newQuery()
        ->loadContexts(['headerDashboard', 'footerDashboard'])
        ->filterForCurrentUser();

    $parentActiveNode = $fullQuery->getActiveNodeParentWithChildren();

    $activo = $fullQuery->get();
    $breadcrumbs = $fullQuery->getBreadcrumbsForCurrentRoute();
    $activeNode = $fullQuery->getCurrentActiveNode();

@endphp

