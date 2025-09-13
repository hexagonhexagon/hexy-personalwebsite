<?php


function generate_secure_nonce() {
    return base64_encode(random_bytes(128));
}

function set_csp_header(string $secure_nonce) {
    $csp_header = <<<END
        default-src 'self';
        script-src 'nonce-$secure_nonce' 'script-dynamic';
        style-src 'nonce-$secure_nonce';
        frame-src https://www.youtube.com;
        object-src 'none';
        base-uri 'none';
    END;
    $csp_header = str_replace("\n", "", $csp_header);
    header("Content-Security-Policy: $csp_header");
}

function set_csp_report_only_header(string $secure_nonce) {
    $csp_header = <<<END
        default-src 'self';
        script-src 'nonce-$secure_nonce' 'script-dynamic';
        style-src 'nonce-$secure_nonce';
        frame-src https://www.youtube.com;
        object-src 'none';
        base-uri 'none';
    END;
    $csp_header = str_replace("\n", "", $csp_header);
    header("Content-Security-Policy-Report-Only: $csp_header");
}

function include_script(string $src, string $secure_nonce) {
    echo <<<END
        <script src="$src" nonce="$secure_nonce"></script>
    END;
}

function include_style(string $src, string $secure_nonce) {
    echo <<<END
        <link rel="stylesheet" href="$src" nonce="$secure_nonce">
    END;
}

function secure_include(array $stylesheets, array $scripts) {
    $secure_nonce = generate_secure_nonce();
    set_csp_report_only_header($secure_nonce);
    include_style("/styles/main.css", $secure_nonce);
    foreach ($stylesheets as $stylesheet) {
        include_style($stylesheet, $secure_nonce);
    }
    foreach ($scripts as $script) {
        include_script($script, $secure_nonce);
    }
}