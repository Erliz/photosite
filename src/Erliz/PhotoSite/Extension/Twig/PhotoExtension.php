<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   21.11.14
 */

namespace Erliz\PhotoSite\Extension\Twig;


use Doctrine\Common\Collections\Collection;
use Erliz\PhotoSite\Service\PhotoService;
use Gedmo\Exception\RuntimeException;
use Twig_SimpleFunction;

class PhotoExtension extends ApplicationAwareExtension
{
    /**
     * @return PhotoService
     */
    private function getPhotoService()
    {
        if (!isset($this->getApp()['photo.service'])) {
            throw new RuntimeException('Not found "photo.service" registered in Application');
        }
        return $this->getApp()['photo.service'];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Photo';
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('photo', array($this, 'photo')),
            new Twig_SimpleFunction('photo_for_page', array($this, 'getPhotoForPage')),
            new Twig_SimpleFunction('photo_pages_count', array($this, 'getPhotoPagesCount'))
        );
    }

    /**
     * @param int $id
     * @param bool $isThumbnail
     * @param string $fileExtension
     *
     * @return string
     */
    public function photo($id, $isThumbnail = false, $fileExtension = 'jpg')
    {
        $staticSettings = $this->getApp()['config']['static'];

        $path = 'files/photo';
        if ($isThumbnail) {
            $path .= '/thumbnail';
        }

        return sprintf(
            '%s://%s/%s/%d.%s',
            $staticSettings['scheme'],
            $staticSettings['host'],
            $path,
            $id,
            $fileExtension
        );
    }

    /**
     * @param Collection $photos
     * @param int $num
     *
     * @return array
     */
    public function getPhotoForPage(Collection $photos, $num)
    {
        return $this->getPhotoService()->getPage($photos, $num);
    }

    /**
     * @param Collection $photos
     *
     * @return int
     */
    public function getPhotoPagesCount(Collection $photos)
    {
        return $this->getPhotoService()->getPagesSize($photos);
    }
}
