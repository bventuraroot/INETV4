@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
$configData = Helper::appClasses();
@endphp

@guest
@php
$isMenu = ($isMenu ?? false);
$isNavbar = ($isNavbar ?? false);
$isFooter = ($isFooter ?? false);
@endphp
@endguest

@auth
@php
$isMenu = ($isMenu ?? true);
$isNavbar = ($isNavbar ?? true);
$isFooter = ($isFooter ?? false);
@endphp
@endauth

@isset($configData["layout"])
@include((( $configData["layout"] === 'horizontal') ? 'layouts.horizontalLayout' :
(( $configData["layout"] === 'blank') ? 'layouts.blankLayout' : 'layouts.contentNavbarLayout') ))
@endisset
