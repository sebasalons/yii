<?php
use yii\helpers\Html;?>
<h1><?php echo $post->title?></h1>
<p><?php echo $post->body?></p>
<p>Created:<?php echo $post->created?></p>
<p>Modified:<?php echo $post->modified?></p>
<p><?php echo Html::a('Volver',['index.php/listarPosts']) ?></td></p>