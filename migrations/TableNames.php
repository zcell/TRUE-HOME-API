<?php


namespace app\migrations;


trait TableNames
{

    public $table_users=  'users';
    public $table_house='house';
    public $table_housing = 'housing';
    public $table_feed_message= 'feed_message';
    public $table_feed_links = 'feed_links';
    public $table_files = 'files';
    public $feed_like= 'feed_like';
    public $feed_dislike= 'feed_dislike';

    public $table_comments= 'comments';
    public $table_comments_like='comment_likes';
    public $table_comments_dislike='comment_dislikes';

    public $table_poll = 'polls';
    public $table_poll_answers='poll_answers';
}