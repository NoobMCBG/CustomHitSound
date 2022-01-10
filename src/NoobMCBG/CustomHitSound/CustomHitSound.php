<?php

declare(strict_types=1);

namespace NoobMCBG\CustomHitSound;

use ReflectionClass;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class CustomHitSound extends PluginBase implements Listener {

	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("CustomHitSound.mcpack", true);
		$manager = $this->getServer()->getResourcePackManager();
		$pack = new ZippedResourcePack($this->getDataFolder() . "CustomHitSound.mcpack");
		$reflection = new ReflectionClass($manager);
		$property = $reflection->getProperty("resourcePacks");
		$property->setAccessible(true);
		$currentResourcePacks = $property->getValue($manager);
		$currentResourcePacks[] = $pack;
		$property->setValue($manager, $currentResourcePacks);
		$property = $reflection->getProperty("uuidList");
		$property->setAccessible(true);
		$currentUUIDPacks = $property->getValue($manager);
		$currentUUIDPacks[strtolower($pack->getPackId())] = $pack;
		$property->setValue($manager, $currentUUIDPacks);
		$property = $reflection->getProperty("serverForceResources");
		$property->setAccessible(true);
		$property->setValue($manager, true);
	}

	/**
	 * @param EntityDamageByEntityEvent $event
	 * @priority HIGHEST
	 */
	public function onHit(EntityDamageByEntityEvent $event){
		$attacker = $event->getDamager();
		$entity = $event->getEntity();
		if($attacker instanceof Player){
			$attacker = $event->getPlayer();
		    $packet = new PlaySoundPacket();
		    $packet->soundName = "CustomHitSound";
		    $packet->x = $attacker->getPosition()->getX();
		    $packet->y = $attacker->getPosition()->getY();
		    $packet->z = $attacker->getPosition()->getZ();
		    $packet->volume = 1;
		    $packet->pitch = 1;
		    $attacker->getNetworkSession()->sendDataPacket($packet);
		}
		if($entity instanceof Player){
			$packet = new PlaySoundPacket();
		    $packet->soundName = "CustomHitSound";
		    $packet->x = $entity->getPosition()->getX();
		    $packet->y = $entity->getPosition()->getY();
		    $packet->z = $entity->getPosition()->getZ();
		    $packet->volume = 1;
		    $packet->pitch = 1;
		    $entity->getNetworkSession()->sendDataPacket($packet);
		}	
	}
}