<?php
// Advanced Stats Plugin - Setup hooks for FPP
// This file provides hooks for the plugin to inject content into FPP pages

// Hook to add JavaScript to all FPP pages
function fpp_advancedstats_header_hook() {
    // Add the header indicator JavaScript
    $pluginName = 'fpp-plugin-AdvancedStats';
    $jsFile = "/plugin.php?plugin={$pluginName}&file=header-indicator.js";
    echo "<script src=\"{$jsFile}\"></script>\n";
}

// Register the hook if FPP supports it
if (function_exists('register_fpp_plugin_hook')) {
    register_fpp_plugin_hook('header', 'fpp_advancedstats_header_hook');
}
?>
