<?php

namespace Mrchlldev\HomeSystem\utils;

use pocketmine\Server;
use pocketmine\world\World;
use pocketmine\entity\Location;
use pocketmine\utils\Config;

class HomeManager {

    /** @var Config $homeData */
    private Config $homeData;

    /**
     * @param string $dataFolder
     */
    public function __construct(string $dataFolder) {
        $this->homeData = new Config($dataFolder . "homes.json", Config::JSON, []);
    }

    /**
     * @param string $playerName
     * @param string $homeName
     * @param Location $location
     * @return void
     */
    public function setHome(string $playerName, string $homeName, Location $location): void {
        $homes = $this->homeData->get($playerName, []);
        $homes[$homeName] = [
            "x" => $location->getX(),
            "y" => $location->getY(),
            "z" => $location->getZ(),
            "world" => $location->getWorld()->getFolderName()
        ];
        $this->homeData->set($playerName, $homes);
        $this->homeData->save();
    }

    /**
     * @param string $playerName
     * @param string $homeName
     * @return ?World
     */
    public function getHomeWorld(string $playerName, string $homeName): ?World {
        $homes = $this->homeData->get($playerName, []);
        if (isset($homes[$homeName])) {
            $data = $homes[$homeName];
            return Server::getInstance()->getWorldManager()->getWorldByName($data["world"]);
        }
        return null;
    }

    /**
     * @param string $playerName
     * @param string $homeName
     * @return ?Vector3
     */
    public function getHome(string $playerName, string $homeName): ?Vector3 {
        $homes = $this->homeData->get($playerName, []);
        if (isset($homes[$homeName])) {
            $data = $homes[$homeName];
            return new Vector3($data["x"], $data["y"], $data["z"]);
        }
        return null;
    }

    /**
     * @param string $playerName
     * @param string $homeName
     * @return bool
     */
    public function removeHome(string $playerName, string $homeName): bool {
        $homes = $this->homeData->get($playerName, []);
        if (isset($homes[$homeName])) {
            unset($homes[$homeName]);
            $this->homeData->set($playerName, $homes);
            $this->homeData->save();
            return true;
        }
        return false;
    }

    /**
     * @param string $playerName
     * @return array
     */
    public function getHomes(string $playerName): array {
        return $this->homeData->get($playerName, []);
    }

    /**
     * @param string $playerName
     * @return int
     */
    public function getHomeCount(string $playerName): int {
        return count($this->getHomes($playerName));
    }
}
