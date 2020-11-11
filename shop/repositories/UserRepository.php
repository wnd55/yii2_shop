<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 11.05.18
 * Time: 16:56
 */

namespace shop\repositories;


use shop\entities\user\User;
use yii\web\NotFoundHttpException;


class UserRepository
{

    /**
     * @param $value
     * @return array|null|User|\yii\db\ActiveRecord
     */

    public function findByUsernameOrEmail($value)
    {


        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();


    }

    /**
     * @param $network
     * @param $identity
     * @return array|null|User|\yii\db\ActiveRecord
     */

    public function findByNetworkIdentity($network, $identity)
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }


    /**
     * @inheritdoc
     */
    private function getBy(array $condition)
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {

            throw new NotFoundHttpException('User not found.');
        }
        return $user;


    }

    /**
     * @inheritdoc
     */

    public function get($id)
    {
        return $this->getBy(['id' => $id]);
    }

    /**
     * @inheritdoc
     */

    public function getByEmail($email)
    {
        return $this->getBy(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public function save(User $user)
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }

    }

    /**
     * @inheritdoc
     */
    public function remove(User $user)
    {
        if (!$user->delete()) {
            throw new \RuntimeException('Removing error.');
        }

    }

    /**
     * @param $token
     * @return bool
     */
    public function existsByPasswordResetToken($token)
    {

        return (bool)User::findByPasswordResetToken($token);


    }

    /**
     * @inheritdoc
     */
    public function getByPasswordResetToken($token)
    {

        return $this->getBy(['password_reset_token' => $token]);

    }

}

