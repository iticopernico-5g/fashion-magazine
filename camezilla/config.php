<?php
use Camezilla\Models\Config;

function init_config(): void {
    add_global_item('config', Config::load(__DIR__ . '/../camezilla.config.json'));
}

function get_config(): Config {
    if (get_global_item('config') === null) {
        throw new Exception("Config not initialized");
    }
    
    return get_global_item('config');
}