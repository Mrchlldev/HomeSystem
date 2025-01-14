<?php

namespace Mrchlldev\HomeSystem;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use Mrchlldev\HomeSystem\commands\SetHomeCommand;
use Mrchlldev\HomeSystem\commands\HomeCommand;
use Mrchlldev\HomeSystem\commands\RemoveHomeCommand;
use Mrchlldev\HomeSystem\commands\GetMyHomeCommand;
use Mrchlldev\HomeSystem\utils\HomeManager;

class Main extends PluginBase {

    public HomeManager $homeManager;
    public array $homeLimits;
    public string $prefix;

    /**
     * @return void
     */
    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->homeLimits = $this->getConfig()->get("home_limits", []);
        $this->prefix = $this->getConfig()->get("prefix");
        $this->homeManager = new HomeManager($this->getDataFolder());

        $this->getServer()->getCommandMap()->register("HomeSystem", new SetHomeCommand($this));
        $this->getServer()->getCommandMap()->register("HomeSystem", new HomeCommand($this));
        $this->getServer()->getCommandMap()->register("HomeSystem", new RemoveHomeCommand($this));
        $this->getServer()->getCommandMap()->register("HomeSystem", new GetMyHomeCommand($this));
    }

    /**
     * @return HomeManager
     */
    public function getHomeManager(): HomeManager {
        return $this->homeManager;
    }

    /**
     * @param $player
     * @return int
     */
    public function getHomeLimitForPlayer($player): int {
        foreach ($this->homeLimits as $permission => $limit) {
            if ($player->hasPermission($permission)) {
                return $limit;
            }
        }
        return $this->homeLimits["default"] ?? 1;
    }

    /**
     * @return string
     */
    public function getPrefix(): string {
        return $this->prefix;
    }

    /**
     * @param Player $player
     * @param string $message
     * @return void
     */
    public function sendMessageWithPrefix(Player $player, string $message): void {
        $player->sendMessage($this->prefix . " " . $message);
    }
}