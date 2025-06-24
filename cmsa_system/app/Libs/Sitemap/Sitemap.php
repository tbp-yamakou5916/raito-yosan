<?php

namespace App\Libs\Sitemap;


use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

class Sitemap {
    use SampleTrait;

    private string $target_route = '';
    private array $sitemap_xml = [];
    private array $ignores = [];
    private string $sitemap_name = '';
    public function __construct() {
        // サイトマップを作成するroutingのnameの先頭
        // frontなど
        $this->target_route = 'front';
        // sitemapのname
        $this->sitemap_name = 'front.sitemap';
        // サイトマップ化しないname
        // 原則getメソッドのみ
        $this->ignores = [
            'front.contact.thanks',
            'front.mypage.*'
        ];

        // データ作成
        $this->makeData();

    }

    /**
     * データ作成
     * @return void
     */
    private function makeData() {
        $routes = Route::getRoutes();

        $uris = [];
        foreach ($routes as $route) {
            $name = $route->getName();
            // 対象ルーティング以外無視
            if(!str_starts_with($name, $this->target_route . '.')) continue;
            // 無視リスト
            foreach ($this->ignores as $ignore) {
                if(str_ends_with($ignore, '*')) {
                    $str = substr($ignore, 0, -1);
                    if(str_starts_with($name, $str)) continue 2;
                } else {
                    if($name==$ignore) continue 2;
                }
            }
            // サイトマップ
            if($name == $this->sitemap_name) continue;
            // GETメソッドのみ
            if(!in_array('GET', $route->methods()) && !$route->uri() !== '/') continue;

            // XML作成用URI作成（参考：FHWの場合）
            if($name=='site.faq.list') {
                // FAQ一覧
                //$uris = array_merge($uris, $this->getFaqList());
            } elseif($name=='site.faq.detail') {
                // FAQ詳細
                //$uris = array_merge($uris, $this->getFaqDetail());
            } elseif($name=='site.works.detail') {
                // 実績詳細
                //$uris = array_merge($uris, $this->getWorksDetail());
            } elseif($name=='site.works.index') {
                // 実績一覧
                //$uris = array_merge($uris, $this->getWorksIndex());
            } else {
                $uris[] = $route->uri();
            }
        }

        // xml <url></url>の作成
        foreach($uris as $uri) {
            $this->sitemap_xml[] = $this->makeXml($uri);
        }
    }

    /**
     * xml <url></url>の作成
     * @param $uri
     * @return string
     */
    private function makeXml($uri)
    {
        // URL
        $url = htmlspecialchars(url($uri));
        // 更新頻度設定
        $frequency = $this->checkFrequency($uri);
        // 優先度設定
        $priority = $this->checkPriority($uri);

        // XML
        $xml = '<url>' . PHP_EOL;
        $xml .= '<loc>' . $url . '</loc>' . PHP_EOL;
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>' . PHP_EOL;
        $xml .= '<changefreq>' . $frequency . '</changefreq>' . PHP_EOL;
        $xml .= '<priority>' . $priority . '</priority>' . PHP_EOL;
        $xml .= '</url>';

        return $xml;
    }

    /**
     * 更新頻度設定
     * Googleでは無視される
     *
     * @param $uri
     * @return string
     */
    private function checkFrequency($uri): string
    {
        $str = 'monthly';
        if($uri=='/') {
            // トップページ
            $str = 'daily';
        }

        return $str;
    }

    /**
     * 優先度設定
     * Googleでは無視される
     *
     * @param $uri
     * @return float|int
     */
    private function checkPriority($uri): float|int
    {
        $float = 0.8;
        if($uri=='/') {
            // トップページ
            $float = 1;
        }

        return $float;
    }

    /**
     * sitemap.xml作成
     * @return \Illuminate\Http\Response
     */
    public function getSitemap(): \Illuminate\Http\Response
    {
        $start = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        $end = '</urlset>';
        // 先頭に追加
        array_unshift($this->sitemap_xml, $start);
        // 最後に追加
        $this->sitemap_xml[] = $end;

        return Response::make(implode(PHP_EOL, $this->sitemap_xml), 200)
            ->header('Content-Type', 'application/xml');
    }
}
