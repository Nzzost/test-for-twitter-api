<?php

namespace backend\modules\app;

use Abraham\TwitterOAuth\TwitterOAuth;
use backend\modules\api\models\Specusertweets;

class TwitterApi
{
    protected $consumer_key         = "RGwrc0ohPMqogjEIVj9JD5Uv9";
    protected $consumer_secret      = "MH212UiVdN3lWRkDGGrludbzxHtJsa2CdSbq90GIAK7vExmp66";
    protected $access_token         = "958689322051538944-1c2jIizKyhMmhayx4STwQl1SKfoPulO";
    protected $access_token_secret  = "WBEr23ocwsrMUbrIqQGmtGMUJKH5jzF4fyqmUBOkuzVUb";
    protected $connection;
    protected $content;


    public function __construct()
    {
        $this->connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->access_token, $this->access_token_secret);
        $this->content = $this->connection->get("account/verify_credentials");
    }

    public function feedRecords($user)
    {
        $last_record = Specusertweets::find()
            ->where(['specuser_id' => $user->u_id])
            ->max('tweet_id');

        if ($last_record) {
            $tweets = $this->connection->get("statuses/home_timeline",  ["screen_name" => $user->name, "since_id" => $last_record]);
        } else {
            $tweets = $this->connection->get("statuses/home_timeline",  ["screen_name" => $user->name, "count" => 10]);
        }
        if (!$tweets) {
            return ['error', 'internal error'];
        }
        $feed = [];
        foreach($tweets as $tweet) {
           $tw              = new Specusertweets();
           $tw->specuser_id = $user->u_id;
           $tw->tweet_id    = $tweet->id_str;
           ($tweet->text) ? $tw->tweet = $tweet->text : $tw->tweet = 'no text';
           ($tweet->entities->hashtags) ? $tw->hashtags = serialize($tweet->entities->hashtags) : $tw->hashtags = 'no tags';
           $tw->save();
           $feed[] = ['user' => $user->name, 'tweet' => $tw->tweet, 'hashtags' => $tw->hashtags];
        }
        return $feed;
    }
}