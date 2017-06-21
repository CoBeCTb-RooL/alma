<h1>Главная</h1>
ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ ГЛАВНАЯ 


<p>
<img src="/upload/images/news/787_5343bf333cc05.jpg">
<img src="<?=Media::img('news/787_5343bf333cc05.jpg&width=130')?>">


<hr>


<?php 
$img = "gallery/486_5342c7eee7113.jpg";?>

<p>



<!--<img src="/include/resize.slonne.php" alt="" style="border: 1px solid red; " />-->
<img src="<?=Media::img2($img.'&width=100')?>" alt="" style="border: 1px solid red; " />
<img src="<?=Media::img2($img.'&width=100&method=crop')?>" alt="" style="border: 1px solid red; " />
<img src="<?=Media::img2($img.'&height=100')?>" alt="" style="border: 1px solid red; " />
<img src="<?=Media::img2($img.'&width=200&height=100')?>" alt="" style="border: 1px solid red; " />

<?php 
vd(Media::img2($img.'&width=100'))?>