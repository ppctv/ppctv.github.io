<?php
if (!defined('puyuetian'))
	exit('403');

$navhtml = template('superadmin:home-nav', TRUE);
if ($_G['GET']['T'])
	$contenthtml = template('superadmin:home-' . $_G['GET']['T'], TRUE);
else
	$contenthtml = template('superadmin:home-info', TRUE);
