<?php

namespace app\models\Entity;

use Yii;
use yii\caching\TagDependency;

/**
 * User ActiveRecord model.
 *
 *
 * Database fields:
 *
 * @property integer    $id
 * @property string     $phone         [varchar(15)]
 * @property string     $password_hash [varchar(255)]
 * @property string     $first_name    [varchar(255)]
 * @property string     $middle_name   [varchar(255)]
 * @property string     $last_name     [varchar(255)]
 * @property int        $status        [smallint(3)]
 * @property int        $role          [smallint(3)]
 * @property string     $avatar        [varchar(255)]
 * @property int        $house_id      [int(11)]
 * @property float      $flat_area     [double]  временно, пока не допилим нормальную интеграцию
 * @property string     $bio
 * @property string     $esia_uid      [varchar(255)]
 * @property string     $vk_uid        [varchar(255)]
 * @property string     $google_uid    [varchar(255)]
 * @property string     $facebook_uid  [varchar(255)]
 * @property string     $yandex_uid    [varchar(255)]
 * @property string     $housing_uid   [varchar(255)]
 *
 * @property-read House $house
 *
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    const DURATION_TOKEN   = 86400; //60*60*24
    const DURATION_REFRESH = 604800;//60*60*24*7

    private static $token;

    public static function tableName()
    {

        return '{{%users}}';
    }

    public static function findIdentity($id)
    {

        return static::findOne(['id' => $id]);
    }

    public static function getCurrentToken()
    {
        return static::$token;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

        static::$token = $token;
        $cache = \Yii::$app->cache;

        $value = $cache->get('auth_' . $token);

        if ($value !== false) {
            return static::findOne(['id' => $value]);
        }
        $value = $cache->get('refresh_' . $token);
        if ($value !== false) {
            $user = static::findOne(['id' => $value]);
            if ($user === null) {
                return null;
            }
            $user->regenerateToken($token);

            return $user;
        }

        return null;
    }

    public static function phoneUnifier($phone)
    {

        $clearedPhone = str_replace(
            [
                ' ',
                '+',
                '(',
                ')',
                '-',
            ],
            '',
            $phone
        );
        if (mb_substr($clearedPhone, 0, 1) === '8') {
            $clearedPhone = '7' . mb_substr($clearedPhone, 1);
        }

        return $clearedPhone;
    }

    public function rules()
    {

        return [
            [['phone'], 'required'],

            'phoneNormalization' => [
                'phone',
                function () {

                    $clearedPhone = static::phoneUnifier($this->phone);
                    if (!ctype_digit($clearedPhone)) {
                        $this->addError('phone', 'Неправильный номер');

                        return;
                    }
                    $this->phone = $clearedPhone;
                },
            ],
        ];
    }

    public function regenerateToken($token)
    {

        $cache = Yii::$app->cache;

        $cache->delete('auth_' . $token);
        $cache->delete('refresh_' . $token);

        $cache->add(
            'auth_' . $token,
            $this->id,
            self::DURATION_TOKEN,
            new TagDependency(['tags' => 'user_' . $this->id])
        );
        $cache->add(
            'refresh_' . $token,
            $this->id,
            self::DURATION_REFRESH,
            new TagDependency(['tags' => 'user_' . $this->id])
        );

        static::$token = $token;
        return $token;
    }

    public function generateTokens()
    {

        $cache = Yii::$app->cache;
        $uniq = uniqid('', true) . uniqid('', true) . uniqid('', true);
        $cache->add(
            'auth_' . $uniq,
            $this->id,
            self::DURATION_TOKEN,
            new TagDependency(['tags' => 'user_' . $this->id])
        );
        $cache->add(
            'refresh_' . $uniq,
            $this->id,
            self::DURATION_REFRESH,
            new TagDependency(['tags' => 'user_' . $this->id])
        );

        return $uniq;
    }

    public function dropTokens()
    {

        $cache = Yii::$app->cache;
        $cache->delete('auth_' . static::$token);
        $cache->delete('refresh_' . static::$token);
    }

    public function validatePassword($password)
    {

        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getId()
    {

        return $this->id;
    }

    public function getAuthKey()
    {

        throw new \Exception("No sessions allowed");
    }

    public function validateAuthKey($authKey)
    {

        throw new \Exception("No sessions allowed");
    }

    public function getHouse()
    {

        return $this->hasOne(House::class, ['id' => 'house_id']);
    }
}