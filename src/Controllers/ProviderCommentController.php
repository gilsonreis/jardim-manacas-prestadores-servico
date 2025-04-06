<?php

namespace App\Controllers;

use App\Models\ProviderComments;
use yii\db\Exception;
use yii\helpers\VarDumper;

class ProviderCommentController extends BaseController
{
    /**
     * @throws Exception
     */
    public function actionCreate($providerId)
    {
        $body = \Yii::$app->request->getRawBody();
        $data = json_decode($body, true);
        $commentText = $data['comment'] ?? null;

        if (empty($commentText)) {
            return $this->asJson(['success' => false, 'message' => 'Comentário vazio.']);
        }

        $model = new ProviderComments();
        $model->provider_id = $providerId;
        $model->user_id = \Yii::$app->user->id;
        $model->comment = $commentText;

        if ($model->save()) {
            return $this->asJson(['success' => true]);
        }

        return $this->asJson(['success' => false, 'message' => 'Erro ao salvar comentário.']);
    }

    public function actionList($providerId)
    {
        $comments = \App\Models\ProviderComments::find()
            ->where(['provider_id' => $providerId])
            ->with('user')
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $result = [];

        foreach ($comments as $comment) {
            $name = $comment->user?->name ?? 'Usuário';
            $photo = $comment->user?->photo;

            if (!$photo) {
                $nameEncoded = urlencode($name);
                $photo = "https://ui-avatars.com/api/?name={$nameEncoded}&background=random";
            }

            $result[] = [
                'id' => $comment->id,
                'name_user' => $name,
                'photo' => $photo,
                'comment' => $comment->comment,
                'created_at' => date('d/m/Y H:i', strtotime($comment->created_at)),
            ];
        }

        return $this->asJson($result);
    }
}
