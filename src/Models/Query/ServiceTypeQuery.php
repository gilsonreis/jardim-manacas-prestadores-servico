<?php

namespace App\Models\Query;

/**
 * This is the ActiveQuery class for [[\App\Models\ServiceType]].
 *
 * @see \App\Models\ServiceType
 */
class ServiceTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \App\Models\ServiceType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \App\Models\ServiceType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
