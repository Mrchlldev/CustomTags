<?php

/*
 * CustomTags Plugin
 * Copyright (C) 2022 DaRealAqua
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace customtags\darealaqua;

use customtags\darealaqua\utils\API;
use pocketmine\event\Listener;
use pocketmine\player\chat\LegacyRawChatFormatter;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerLoginEvent;
use customtags\darealaqua\Main;

class EventListener implements Listener {

    /**
     * @param Main $main
     */
    public function __construct(
        private Main $main
    ) {
    }

    /**
     * @priority HIGHEST
     * @param PlayerChatEvent $event
     * @return void
     */
    public function onPlayerChat(PlayerChatEvent $event) : void {
        $player = $event->getPlayer();
        $message = $event->getMesssge();
        $api = $this->main->getAPI();
        if($event->isCancelled()) return;
        if(Main::getInstance()->pureChat !== null){
            $worldName = Main::getInstance()->pureChat->getConfig->get("enable-multiworld-chat") ? $player->getWorld->getFolderName() : null;
            $chatFormatter = Main::getInstance()->pureChat->getChatFormat($player, $message, $worldName);
        $chatFormatter = str_replace("{tag}", ($api->getPlayerTag($player, API::CHAT_FORMAT) ? ""), $chatFormatter);
        $event->setFormatter(new LegacyRawChatFormatter((string)$chatFormatter));
    }

    /**
     * @priority NORMAL
     * @param PlayerLoginEvent $event
     * @return void
     */
    public function onPlayerLogin(PlayerLoginEvent $event) : void {
        $player = $event->getPlayer();
        $api = $this->main->getAPI();
        if (!$api->existPlayer($player)) {
            $api->createPlayer($player);
        }
    }

}
