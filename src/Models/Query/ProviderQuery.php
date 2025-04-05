<?php

namespace App\Models\Query;

/**
 * This is the ActiveQuery class for [[\App\Models\ProviderType]].
 *
 * @see \App\Models\Provider
 */
class ProviderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \App\Models\Provider[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \App\Models\Provider|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
