<?php

function getValidPath($command, $default) {
    $path = trim(shell_exec($command));
    return (file_exists($path) && is_executable($path)) ? $path : $default;
}

return [
    'chrome_path' => env('CHROME_PATH', getValidPath('which chromium-browser || which chromium || which google-chrome', '/usr/bin/chromium-browser')),
    'node_path' => env('NODE_PATH', getValidPath('which node', '/usr/bin/node')),
    'npm_path' => env('NPM_PATH', getValidPath('which npm', '/usr/bin/npm')),
];
