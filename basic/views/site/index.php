<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
<div class="site-index">
    <table>
        <tr>
            <th>id</th>
            <th>title</th>
            <th>body</th>
            <th>created</th>
            <th>modified</th>
        </tr>
        <?php foreach($posts as $post):?>
            <tr>
                <td><?php echo $post->id?></td>
                <td><?php echo Html::a($post->title,'viewPost/' . $post->id)?></td>
                <td><?php echo $post->body?></td>
                <td><?php echo $post->created?></td>
                <td><?php echo $post->modified?></td>
                <td><?php echo Html::a('Update','updatePost/' . $post->id)?></td>
                <td><?php echo Html::a('Delete','deletePost/' . $post->id)?></td>
            </tr>
        <?php endforeach?>
    </table>
    <p><?php echo Html::a('Create Post','createPost') ?></p>
    <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
        <div class="alert alert-success">
            <?php echo $message?>
        </div>
    <?php endforeach;?>
    <div><?php echo Yii::$app->session->get('user.attribute');?></div>
    <div><?php
        Yii::$app->language = 'es';
        echo Yii::t('yii', 'prueba');?></div>
</div>
