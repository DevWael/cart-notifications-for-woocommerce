<?php
// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace Devwael\CartNotificationsWc\Frontend\Assets;

interface Assets {

	public function load_css(): void;

	public function load_js(): void;
}