<?php

namespace App\Libs\Sitemap;


use App\Models\Master\BusinessCategory;
use App\Models\Master\FaqCategory;
use App\Models\Work\Work;

trait SampleTrait {

    /**
     * FAQ一覧
     *
     * @return mixed
     */
    private function getFaqList() {
        $loads = [
            'valid_faqs',
        ];
        return FaqCategory::where('is_valid', 1)
            ->with($loads)
            ->get()
            ->filter(function($row) {
                return $row->valid_faqs->isNotEmpty();
            })
            ->map(fn($row) => 'faq/' . $row->en)
            ->toArray();
    }

    /**
     * FAQ詳細
     *
     * @return mixed
     */
    private function getFaqDetail() {
        $loads = [
            'valid_faqs',
        ];
        return FaqCategory::where('is_valid', 1)
            ->with($loads)
            ->get()
            ->filter(function($row) {
                return $row->valid_faqs->isNotEmpty();
            })
            ->map(function($row) {
                $arr = [];
                foreach ($row->valid_faqs as $faq) {
                    $arr[] =  'faq/detail/' . $faq->id;
                }
                return $arr;
            })
            ->flatten()
            ->toArray();
    }

    /**
     * 実績詳細
     *
     * @return mixed
     */
    private function getWorksDetail() {
        $loads = [
            'business_category',
        ];
        return Work::where('is_view', 1)
            ->where('is_detail', 1)
            ->with($loads)
            ->get()
            ->map(function($work) {
                return 'detail/' . $work->business_category->hash . '/' . $work->path;
            })
            ->toArray();
    }
    /**
     * 実績一覧
     *
     * @return mixed
     */
    private function getWorksIndex() {
        $loads = [
            'works',
        ];
        $arr = ['works'];
        BusinessCategory::where('is_footer', 1)
            ->with($loads)
            ->get()
            ->filter(function($row) {
                return $row->works->isNotEmpty();
            })
            ->map(function($row) use(&$arr) {
                // カテゴリトップ
                $arr[] = 'works/' . $row['hash'];
                // ページャー 2ページ目以降
                $count = $row->works->count();
                $pages = ceil(($count - 11) / 12);
                if($pages > 1) {
                    foreach(range(2, $pages) as $page) {
                        $arr[] = 'works/' . $row['hash'] . '/page/' . $page;
                    }
                }
            });
        return $arr;
    }
}
