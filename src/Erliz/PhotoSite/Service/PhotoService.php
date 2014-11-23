<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 23.11.2014
 */

namespace Erliz\PhotoSite\Service;


use Doctrine\Common\Collections\Collection;

class PhotoService
{
    private $pages;

    private $fullWidth = 945;
    private $horizontalWidth = 274; // 264 + 10
    private $verticalWidth = 129; // 119 + 10

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
}
