<?php

require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;
use Dotenv\Dotenv;

class DownloadTwitter {

    private $file_pass = 'downloaded/';
    private $twitter;

    function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $this->twitter = new TwitterOAuth(
            getenv('TWITTER_API_KEY'),
            getenv('TWITTER_API_SECRET_KEY'),
            getenv('TWITTER_ACCESS_TOKEN'),
            getenv('TWITTER_ACCESS_TOKEN_SECRET')
        );
    }


    public function extractPicturesFromTweets($tweets)
    {
        foreach($tweets as $key1 => $val1){
            //画像のツイートか判定
            if(isset($val1['entities']['media'])){
                foreach($val1['entities']['media'] as $key2 => $val2){
                    $data = file_get_contents($val2['media_url_https']); //画像URLからデータを取得
                    $timestamp = strtotime($val1['created_at']); //投稿日時
                    $file_name = $this->file_pass.date('Ymd-His', $timestamp).'_'.($key2 + 1).'_'.$val1['user']['name'].'.jpg'; //ファイル名
                    echo $file_name.PHP_EOL;
                    file_put_contents($file_name, $data); //ファイルを保存
                    sleep(3); //負荷軽減のため間隔を置く
                }
            } else {
                echo 'no_image'.PHP_EOL;
            }
        }
    }

    public function searchImage($word, $num = 100, $opt = [])
    {
        $params = array_merge($opt, [
            'lang' => 'ja',
            'locale' => 'ja',
            'result_type' => 'mixed',
            'count' => 100,
            "q" => $word,]);

        $res = $this->twitter->get("search/tweets", $params);
        $tweets = json_decode(json_encode($res), true);
        if (isset($tweets)) {
            $tweets_statuses = $tweets['statuses'];
            $this->extractPicturesFromTweets($tweets_statuses);

            $list_num = count($tweets_statuses)-1;
            $max_id = isset($tweets_statuses[$list_num]) ? $tweets_statuses[$list_num]['id']: null;
            if (isset($max_id)) {
                $this->searchImage($word, $num, ['max_id' => $max_id]);
            }
        }
    }
}
