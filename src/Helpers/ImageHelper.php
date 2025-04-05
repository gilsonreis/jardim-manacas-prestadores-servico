<?php

namespace App\Helpers;

use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class ImageHelper
{
    /**
     * Redimensiona uma imagem para o tamanho máximo (proporcional).
     *
     * @param string $source Caminho absoluto da imagem original
     * @param int $maxWidth Largura máxima
     * @param int $maxHeight Altura máxima
     * @param int $quality Qualidade da imagem final (1 a 100)
     * @return bool true em caso de sucesso
     */
    public static function resizeProportional(string $source, int $maxWidth = 300, int $maxHeight = 300, int $quality = 85): bool
    {
        try {
            Image::thumbnail($source, $maxWidth, $maxHeight)
                ->save($source, ['quality' => $quality]);
            return true;
        } catch (\Throwable $e) {
            \Yii::error('Erro ao redimensionar imagem: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * Redimensiona cortando para exatamente o tamanho desejado (quadrado, etc).
     *
     * @param string $source Caminho absoluto da imagem original
     * @param int $width Largura final
     * @param int $height Altura final
     * @param int $quality Qualidade da imagem final (1 a 100)
     * @return bool true em caso de sucesso
     */
    public static function resizeCrop(string $source, int $width = 300, int $height = 300, int $quality = 85): bool
    {
        try {
            Image::getImagine()
                ->open($source)
                ->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND)
                ->save($source, ['quality' => $quality]);
            return true;
        } catch (\Throwable $e) {
            \Yii::error('Erro ao redimensionar com crop: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}