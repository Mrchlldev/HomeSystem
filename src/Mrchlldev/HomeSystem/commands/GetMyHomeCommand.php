<?php

namespace Mrchlldev\HomeSystem\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Mrchlldev\HomeSystem\Main;

class GetMyHomeCommand extends Command {

    /** @var Main $plugin */
    private Main $plugin;

    /**
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        parent::__construct("getmyhome", "Show all your homes.", "/getmyhome");
        $this->setPermission("homesystem.command.getmyhome");
        $this->plugin = $plugin;
    }

    /**
     * @param CommandSender $sender
     * @param string $label
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $label, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game.");
            return;
        }

        $homeManager = $this->plugin->getHomeManager();
        $homes = $homeManager->getHomes($sender->getName());

        if (!isset($homes)) {
            $this->plugin->sendMessageWithPrefix($sender, "§cYou don't have any homes set.");
            return;
        }

        $homeList = implode(", ", array_keys($homes));
        $this->plugin->sendMessageWithPrefix($sender, "§aYour homes:§e $homeList");
    }
}