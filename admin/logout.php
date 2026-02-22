<?php
// File: admin/logout.php
require_once dirname(__DIR__) . '/includes/session.php';
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

session_destroy();
redirect(SITE_URL . '/admin/login.php');