<?php

namespace skh6075\lib\modelskin;

use pocketmine\entity\Skin;

final class ModelSkin{

	public static function makeGeometrySkin(Skin $defaultSkin, string $path, string $geometryName): Skin{
		if(!file_exists($path . $geometryName . ".json") && !file_exists($path . $geometryName . ".png")){
			return $defaultSkin;
		}

		$image = imagecreatefrompng($path . $geometryName . ".png");
		$bytes = "";
		$size = getimagesize($path . $geometryName . ".png")[1];
		for($y = 0; $y < $size; $y++){
			for($x = 0; $x < 64; $x++){
				$colorat = imagecolorat($image, $x, $y);
				$a = ((~((int) ($colorat >> 24))) << 1) & 0xff;
				$r = ($colorat >> 16) & 0xff;
				$g = ($colorat >> 8) & 0xff;
				$b = $colorat & 0xff;
				$bytes .= chr($r) . chr($g) . chr($b) . chr($a);
			}
		}
		imagedestroy($image);
		return new Skin($defaultSkin->getSkinId(), $bytes, "", "geometry." . $geometryName, file_get_contents($path . $geometryName . ".json"));
	}

	public static function skinToMap(Skin $skin): array{
		return [
			base64_encode($skin->getSkinId()),
			base64_encode($skin->getSkinData()),
			base64_encode($skin->getCapeData()),
			base64_encode($skin->getGeometryName()),
			base64_encode($skin->getGeometryData())
		];
	}

	public static function mapToSkin(array $map): Skin{
		return new Skin(base64_decode($map[0]), base64_decode($map[1]), base64_decode($map[2]), base64_decode($map[3]), base64_decode($map[4]));
	}
}