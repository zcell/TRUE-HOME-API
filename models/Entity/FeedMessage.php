<?php


namespace app\models\Entity;


use app\behaviors\SaveRelationBehavior;
use app\models\Enums\Feed\FeedTypeEnum;
use app\models\Enums\Feed\FeedVisibilityEnum;
use app\models\Search\Feed\FeedMessageSearch;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 *
 * @property int       $id         [int(11)]
 * @property int       $author_id  [int(11)]
 * @property string    $title      [varchar(255)]
 * @property string    $text
 * @property int       $due_date   [int(11)]
 * @property int       $start_date [int(11)]
 * @property int       $created_at [int(11)]
 * @property int       $updated_at [int(11)]
 * @property int       $visibility [smallint(3)]
 * @property int       $type       [smallint(3)]
 *
 * @property-read User $author
 * @property-read FeedLinks[] $links
 * @property-read FeedMessage[] $linked
 * @property-read Poll $poll
 */
class FeedMessage extends ActiveRecord
{


    public function behaviors()
    {

        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            [
                'class'     => SaveRelationBehavior::class,
                'relations' => [
                    'files','links','poll'
                ],
            ]
        ];
    }

    public static function tableName()
    {

        return '{{%feed_message}}';
    }

    public function rules()
    {

        return [
          [['title','text'],'string'],
          [['title','text','visibility','type'],'required'],
          ['status','integer'],
          [['due_date','start_date','visibility','type'],'integer'],
          ['visibility','in','range'=>array_keys(FeedVisibilityEnum::getNames())],
          ['type','in','range'=>array_keys(FeedTypeEnum::getNames())],
          ['author_id','default','value'=>\Yii::$app->user->id],

          [['files','links','poll'],'safe']
        ];
    }


    public function getAuthor()
    {

        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    public function getFiles(){
        return $this->hasMany(File::class,['feed_id'=>'id']);
    }

    public function getLinks(){
        return $this->hasMany(FeedLinks::class,['feed_id'=>'id']);
    }

    public function getLinked(){
        return $this->hasMany(static::class,['id'=>'link_id'])->viaTable('{{%feed_links}}',['feed_id'=>'id']);
    }

    public function getPoll(){
        return $this->hasMany(Poll::class,['feed_id'=>'id']);
    }

    public function getLikes(){
        return $this->hasMany(FeedLike::class,['feed_id'=>'id']);
    }

    public function getDislikes(){
        return $this->hasMany(FeedDislike::class,['feed_id'=>'id']);
    }

    public function getComments(){
        return $this->hasMany(Comments::class,['feed_id'=>'id'])->orderBy('created_at desc');
    }
}