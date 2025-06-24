<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master\Prefecture;

class PrefectureSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void {
        $prefectures = array(
            array('id' => '1','name' => '北海道','short' => '北海道','division' => NULL,'hash' => 'hokkaido','sequence' => '1'),
            array('id' => '2','name' => '青森県','short' => '青森','division' => '県','hash' => 'aomori','sequence' => '2'),
            array('id' => '3','name' => '岩手県','short' => '岩手','division' => '県','hash' => 'iwate','sequence' => '3'),
            array('id' => '4','name' => '宮城県','short' => '宮城','division' => '県','hash' => 'miyagi','sequence' => '4'),
            array('id' => '5','name' => '秋田県','short' => '秋田','division' => '県','hash' => 'akita','sequence' => '5'),
            array('id' => '6','name' => '山形県','short' => '山形','division' => '県','hash' => 'yamagata','sequence' => '6'),
            array('id' => '7','name' => '福島県','short' => '福島','division' => '県','hash' => 'fukushima','sequence' => '7'),
            array('id' => '8','name' => '茨城県','short' => '茨城','division' => '県','hash' => 'ibaraki','sequence' => '8'),
            array('id' => '9','name' => '栃木県','short' => '栃木','division' => '県','hash' => 'tochigi','sequence' => '9'),
            array('id' => '10','name' => '群馬県','short' => '群馬','division' => '県','hash' => 'gunma','sequence' => '10'),
            array('id' => '11','name' => '埼玉県','short' => '埼玉','division' => '県','hash' => 'saitama','sequence' => '11'),
            array('id' => '12','name' => '千葉県','short' => '千葉','division' => '県','hash' => 'chiba','sequence' => '12'),
            array('id' => '13','name' => '東京都','short' => '東京','division' => '都','hash' => 'tokyo','sequence' => '13'),
            array('id' => '14','name' => '神奈川県','short' => '神奈川','division' => '県','hash' => 'kanagawa','sequence' => '14'),
            array('id' => '15','name' => '新潟県','short' => '新潟','division' => '県','hash' => 'niigata','sequence' => '15'),
            array('id' => '16','name' => '富山県','short' => '富山','division' => '県','hash' => 'toyama','sequence' => '16'),
            array('id' => '17','name' => '石川県','short' => '石川','division' => '県','hash' => 'ishikawa','sequence' => '17'),
            array('id' => '18','name' => '福井県','short' => '福井','division' => '県','hash' => 'fukui','sequence' => '18'),
            array('id' => '19','name' => '山梨県','short' => '山梨','division' => '県','hash' => 'yamanashi','sequence' => '19'),
            array('id' => '20','name' => '長野県','short' => '長野','division' => '県','hash' => 'nagano','sequence' => '20'),
            array('id' => '21','name' => '岐阜県','short' => '岐阜','division' => '県','hash' => 'gifu','sequence' => '21'),
            array('id' => '22','name' => '静岡県','short' => '静岡','division' => '県','hash' => 'shizuoka','sequence' => '22'),
            array('id' => '23','name' => '愛知県','short' => '愛知','division' => '県','hash' => 'aichi','sequence' => '23'),
            array('id' => '24','name' => '三重県','short' => '三重','division' => '県','hash' => 'mie','sequence' => '24'),
            array('id' => '25','name' => '滋賀県','short' => '滋賀','division' => '県','hash' => 'shiga','sequence' => '25'),
            array('id' => '26','name' => '京都府','short' => '京都','division' => '府','hash' => 'kyoto','sequence' => '26'),
            array('id' => '27','name' => '大阪府','short' => '大阪','division' => '府','hash' => 'osaka','sequence' => '27'),
            array('id' => '28','name' => '兵庫県','short' => '兵庫','division' => '県','hash' => 'hyogo','sequence' => '28'),
            array('id' => '29','name' => '奈良県','short' => '奈良','division' => '県','hash' => 'nara','sequence' => '29'),
            array('id' => '30','name' => '和歌山県','short' => '和歌山','division' => '県','hash' => 'wakayama','sequence' => '30'),
            array('id' => '31','name' => '鳥取県','short' => '鳥取','division' => '県','hash' => 'tottori','sequence' => '31'),
            array('id' => '32','name' => '島根県','short' => '島根','division' => '県','hash' => 'shimane','sequence' => '32'),
            array('id' => '33','name' => '岡山県','short' => '岡山','division' => '県','hash' => 'okayama','sequence' => '33'),
            array('id' => '34','name' => '広島県','short' => '広島','division' => '県','hash' => 'hiroshima','sequence' => '34'),
            array('id' => '35','name' => '山口県','short' => '山口','division' => '県','hash' => 'yamaguchi','sequence' => '35'),
            array('id' => '36','name' => '徳島県','short' => '徳島','division' => '県','hash' => 'tokushima','sequence' => '36'),
            array('id' => '37','name' => '香川県','short' => '香川','division' => '県','hash' => 'kagawa','sequence' => '37'),
            array('id' => '38','name' => '愛媛県','short' => '愛媛','division' => '県','hash' => 'ehime','sequence' => '38'),
            array('id' => '39','name' => '高知県','short' => '高知','division' => '県','hash' => 'kochi','sequence' => '39'),
            array('id' => '40','name' => '福岡県','short' => '福岡','division' => '県','hash' => 'fukuoka','sequence' => '40'),
            array('id' => '41','name' => '佐賀県','short' => '佐賀','division' => '県','hash' => 'saga','sequence' => '41'),
            array('id' => '42','name' => '長崎県','short' => '長崎','division' => '県','hash' => 'nagasaki','sequence' => '42'),
            array('id' => '43','name' => '熊本県','short' => '熊本','division' => '県','hash' => 'kumamoto','sequence' => '43'),
            array('id' => '44','name' => '大分県','short' => '大分','division' => '県','hash' => 'oita','sequence' => '44'),
            array('id' => '45','name' => '宮崎県','short' => '宮崎','division' => '県','hash' => 'miyazaki','sequence' => '45'),
            array('id' => '46','name' => '鹿児島県','short' => '鹿児島','division' => '県','hash' => 'kagoshima','sequence' => '46'),
            array('id' => '47','name' => '沖縄県','short' => '沖縄','division' => '県','hash' => 'okinawa','sequence' => '47')
        );

        foreach($prefectures as $p) {
            Prefecture::create([
                'id' => $p['id'],
                'name' => $p['name'],
                'short' => $p['short'],
                'division' => $p['division'],
                'hash' => $p['hash'],
                'sequence' => $p['sequence'],
            ]);
        }

    }
}
