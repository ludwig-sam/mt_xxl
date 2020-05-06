<?php namespace App\Service\Wechat;


use App\Service\Wechat\Contracts\MediaInterface;

class Media  extends Wechat implements MediaInterface {


    public function uploadMediaImage($path)
    {
        return $this->parseResult($this->serve()->media->uploadImage($path))->isSuccess();
    }

    public function uploadArticleImage($path)
    {
        return $this->parseResult($this->serve()->material->uploadArticleImage($path))->isSuccess();
    }

    public function uploadThumb($path)
    {
        return $this->parseResult($this->serve()->material->uploadThumb($path))->isSuccess();
    }

    public function uploadImage($path)
    {
        return $this->parseResult($this->serve()->material->uploadImage($path))->isSuccess();
    }

    public function uploadArticle($articles)
    {
        return $this->parseResult($this->serve()->material->uploadArticle($articles))->isSuccess();
    }

    public function updateArticle($mediaId, $article, $index)
    {
        return $this->parseResult($this->serve()->material->updateArticle($mediaId, $article, $index))->isSuccess();
    }

    public function limit($type, $start, $number = 20)
    {
        return $this->parseResult($this->serve()->material->list($type, $start, $number))->getData();
    }

}