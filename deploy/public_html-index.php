<?php

/**
 * Optional redirect for cPanel when the app lives in /resume-builder/
 * but visitors open the domain root (public_html/).
 */

header('Location: /resume-builder/', true, 302);
exit;
