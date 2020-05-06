<?php
/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2018/7/8
 * Time: 下午2:04
 */

namespace App\Service\Material\Materials;


use App\Models\MaterialModel;
use App\Models\Materials\MaterialArticleModel;
use App\Service\Material\Contracts\GetTypeTrait;
use App\Service\Material\Contracts\MaterialAbsctracts;
use App\Service\Wechat\Helper\ImageHelper;
use App\Service\Wechat\Media;
use Illuminate\Support\Collection;

class ArticleMaterial extends MaterialAbsctracts
{
    use GetTypeTrait;

    public function save(MaterialModel $materialModel, Collection &$material)
    {
        $mdata = [
            'title'       => $material->get('title'),
            'mp_media_id' => $material->get('media_id')
        ];

        if ($materialModel->id) {
            return $materialModel->edit($materialModel->id, [
                'articles' => json_encode($material->get('articles'), JSON_UNESCAPED_UNICODE)
            ], $mdata);
        }

        return $materialModel->add($this->getType(), [
            'media_id' => $material->get('media_id'),
            'articles' => json_encode($material->get('articles'), JSON_UNESCAPED_UNICODE)
        ], $mdata);
    }

    public function updateUpload(Collection &$material)
    {
        $wechatMediaService = new Media();
        $articles           = $material->get('articles');

        $media_id = (new MaterialArticleModel())->where('material_id', $material->get('id'))->value('media_id');

        foreach ($articles as $index => $row) {
            $ret = $wechatMediaService->updateArticle($media_id, $articles, $index);

            if (!$ret) {
                $this->setError($wechatMediaService->result()->getMsg());
                return false;
            }
        }

        return true;
    }

    private function decodeThumbUrl(&$articles)
    {
        foreach ($articles as &$article) {
            $article['thumb_url'] = ImageHelper::decode(array_get($article, 'thumb_url'));
        }
    }

    public function upload(Collection &$material)
    {
        $wechatMediaService = new Media();
        $articles           = $material->get('articles');

        $this->decodeThumbUrl($articles);

        $ret = $wechatMediaService->uploadArticle($articles);

        if (!$ret) {
            $this->setError($wechatMediaService->result()->getMsg());
            return false;
        }

        $material->offsetSet('media_id', $wechatMediaService->result()->getData()->get('media_id'));

        return true;
    }

    public function limitFilter($list)
    {
        $items = $list['data'];

        foreach ($items as &$row) {
            $articles        = json_decode($row['articles'], true);
            $row['articles'] = $this->encodeArticleThumbUrl($articles);
        }

        $list['data'] = $items;

        return $list;
    }

    private function encodeArticleThumbUrl(array $articles)
    {
        foreach ($articles as &$article) {
            $article['thumb_url'] = ImageHelper::encode(array_get($article, 'thumb_url'));
        }
        return $articles;
    }

    public function getFilter($row)
    {
        $row->articles = json_decode($row->articles, true);

        $row->articles = $this->encodeArticleThumbUrl($row->articles);

        return $row;
    }
}