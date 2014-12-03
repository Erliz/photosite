<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 23.11.2014
 */

namespace Erliz\PhotoSite\Service;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Erliz\PhotoSite\Entity\Album;
use Erliz\PhotoSite\Entity\Photo;
use Silex\Application;

class PhotoService
{
    /** @var Application */
    private $app;
    private $pages;

    private $fullWidth = 945;
    private $horizontalWidth = 274; // 264 + 10
    private $verticalWidth = 129; // 119 + 10

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param Collection $photos
     */
    private function renderPages(Collection $photos)
    {
        $horizontal = $photos->filter(function ($item) { return !$item->isVertical(); });
        $vertical = $photos->filter(function ($item) { return $item->isVertical(); });
        $pages = array();

        // first generate horizontal blocks
        if (count($horizontal) > 0) {
            $horizontalOdd = (bool)(count($horizontal) % 2);
            $horizontalPackets = array_chunk($horizontal->toArray(), 2);
            $horizontalBlocks = array();
            foreach ($horizontalPackets as $packet) {
                $horizontalBlocks[] = array('is_vertical' => 0, 'photos' => $packet);
            }
            // fucking with the first page
            $horizontalLastBlock = array_pop($horizontalBlocks);
            if ($horizontalOdd) {
                $pages[0][0]=$horizontalLastBlock;
                unset($horizontalLastBlock);
            } else {
                $pages[0][0]=array('is_vertical' => 0, 'photos' => array(array_shift($horizontalLastBlock['photos'])));
            }
            for($i=0;$i<2;$i++){
                if(count($horizontalBlocks)>0){
                    $pages[0][] = array_shift($horizontalBlocks);
                }
            }
            // end
            $pages = array_merge($pages, array_chunk($horizontalBlocks, floor($this->fullWidth / $this->horizontalWidth)));
        }

        if (count($vertical) > 0) {
            $verticalOdd = (bool)(count($vertical) % 2);
            $verticalPackets = array_chunk($vertical->toArray(), 2);
            $verticalBlocks = array();
            foreach ($verticalPackets as $packet) {
                $verticalBlocks[] = array('is_vertical' => 1, 'photos' => $packet);
            }
            if ($verticalOdd) {
                $verticalLastBlock = array_pop($verticalBlocks);
            }
            $lastPage = count($pages) - 1;
            $lastPageBlocksCount = count($pages[$lastPage]);
            if($lastPageBlocksCount<3){
                $freeBlocksForVertical = floor(
                    ($this->fullWidth - $this->horizontalWidth * $lastPageBlocksCount) / $this->verticalWidth
                );
                for($i=0; $i<$freeBlocksForVertical; $i++){
                    if(count($verticalBlocks)>0){
                        $pages[$lastPage][]=array_shift($verticalBlocks);
                    }
                }
            }
            if(count($verticalBlocks)>0){
                $pages = $pages + array_chunk($verticalBlocks, floor($this->fullWidth / $this->verticalWidth));
            }
        }

        if(!empty($horizontalLastBlock) || !empty($verticalLastBlock)){
            $lastPage = count($pages) - 1;
            $size = $this->fullWidth;
            foreach($pages[$lastPage] as $block){
                $size = $size - ($block['is_vertical'] ? $this->verticalWidth : $this->horizontalWidth);
            }

            if (!empty($verticalLastBlock)) {
                $block = $verticalLastBlock;
                if ($size >= $this->verticalWidth) {
                    $pages[$lastPage][] = $block;
                    $size = $size - $this->verticalWidth;
                } else {
                    $lastPage = $lastPage + 1;
                    $pages[] = array();
                    $pages[$lastPage][] = $block;
                    $size = $this->fullWidth - $this->verticalWidth;
                }
            }

            if (!empty($horizontalLastBlock)) {
                $block = $horizontalLastBlock;
                if ($size >= $this->horizontalWidth) {
                    $pages[$lastPage][] = $block;
                    $size = $size - $this->horizontalWidth;
                } else {
                    $lastPage = $lastPage + 1;
                    $pages[] = array();
                    $pages[$lastPage][] = $block;
                    $size = $this->fullWidth - $this->verticalWidth;
                }
            }
        }

        $this->pages = $pages;
    }

    public function getPage(Collection $photos, $num){
        if(!$this->pages){
            $this->renderPages($photos);
        }
        return $this->pages[$num];
    }

    public function getPagesSize(Collection $photos){
        if(!$this->pages){
            $this->renderPages($photos);
        }
        return count($this->pages);
    }

    /**
     * @param Album $album
     * @return array
     */
    public function upload(Album $album)
    {
        /** @var EntityManager $em */
        $em = $this->getApp()['orm.em'];
        /** @var JqueryFileUploadService $fileUpload */
        $fileUpload = $this->getApp()['fileupload'];
        $fileUploadResponse = $fileUpload->get_response();
        $result = array();
        if (isset($fileUploadResponse) && count($fileUploadResponse['files']) > 0) {
            foreach ($fileUploadResponse['files'] as $file) {
                $this->create($album, $file);

                $result[]=$file;
            }

            $em->flush();
        }

        return $result;
    }

    /**
     * @param string $title
     * @return string
     */
    private function trimTitle($title)
    {
        return trim(strip_tags(str_replace('.jpg', '', $title)));
    }

    /**
     * @param Album $album
     * @param \stdClass $file
     *
     * @return Photo
     */
    private function create(Album $album, \stdClass $file)
    {
        $config = $this->app['config'];
        $pathTmp = $config['app']['path'] . '/' . $config['static']['path']['file'] . '/temp/';
        $pathTmpThumbnail = $pathTmp . 'thumbnail/';
        $path = $config['app']['path'] . '/' . $config['static']['path']['file'] . '/photo/';
        $pathThumbnail = $path . 'thumbnail/';

        $pathTmpFile = $pathTmp . $file->name;
        $pathTmpThumbnailFile = $pathTmpThumbnail . $file->name;
        if (!is_file($pathTmpFile)) {
            throw new \RuntimeException('No img file found "' . $pathTmpFile . '"');
        }
        if (!is_file($pathTmpThumbnailFile)) {
            throw new \RuntimeException('No img file found "' . $pathTmpThumbnailFile . '"');
        }
        list($w, $h) = getimagesize($pathTmpFile);

        /** @var EntityManager $em */
        $em = $this->getApp()['orm.em'];

        $photo = new Photo();
        $photo->setTitle($this->trimTitle($file->name));
        $photo->setAlbum($album);
        $photo->setVertical($w < $h);

        $em->persist($photo);
        $em->flush();

        $photoName = $photo->getId() . '.jpg';
        $pathFile = $path . $photoName;
        $pathFileTbn = $pathThumbnail . $photoName;
        if (copy($pathTmpFile, $pathFile)) {
            unlink($pathTmpFile);
        } else {
            throw new \RuntimeException('Couldn`t copy file from "' . $pathTmpFile . '" to "' . $pathFile . '"');
        }
        if (!is_dir($pathThumbnail)) {
            mkdir($pathThumbnail);
        }
        if (copy($pathTmpThumbnailFile, $pathFileTbn)) {
            unlink($pathTmpThumbnailFile);
        } else {
            throw new \RuntimeException('Couldn`t copy file from "' . $pathTmpThumbnailFile . '" to "' . $pathFileTbn . '"');
        }
        // replace old file name
        $file->url = str_replace(
            array($file->name, 'temp'),
            array($photoName, 'photo'),
            $file->url
        );
        $file->thumbnailUrl = str_replace(
            array($file->name, 'temp'),
            array($photoName, 'photo'),
            $file->thumbnailUrl
        );
        unset($file->deleteType, $file->deleteUrl);

    }

    /**
     * @param Collection $photos
     * @param array $orderIds
     *
     * @return Collection
     */
    public function setWight(Collection $photos, array $orderIds)
    {
        $newWight = array_flip($orderIds);

        foreach ($photos as $photo) {
            if(isset($newWight[$photo->getId()])){
                $photo->setWeight($newWight[$photo->getId()]);
            }
        }

        return $photos;
    }

    /**
     * @return Application
     */
    private function getApp()
    {
        return $this->app;
    }
}
