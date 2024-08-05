<?php

return [
    'chrome_path' => env('CHROME_PATH', '/snap/bin/chromium'),
    'node_path' => env('NODE_PATH', getValidPath('which node', '/usr/bin/node')),
    'npm_path' => env('NPM_PATH', getValidPath('which npm', '/usr/bin/npm')),
];
