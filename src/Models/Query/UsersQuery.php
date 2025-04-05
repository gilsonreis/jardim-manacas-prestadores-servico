<?php

namespace App\Models\Query;

/**
 * This is the ActiveQuery class for [[\App\Models\Users]].
 *
 * @see \App\Models\User
 */
class UsersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \App\Models\User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \App\Models\User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
