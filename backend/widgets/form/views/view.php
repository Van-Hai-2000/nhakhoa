<?php

use yii\helpers\Url;
use common\components\ClaHost;
use common\components\ClaLid;
?>
<?php
if (isset($products) && $products) {
    ?>
    <div class="categories-product bg-cate-shadow">
        <div class="title-categories-product">
            <div class="container">
                <h2>
                    <a class="button-ect" href="<?= Url::to(['/product/product/brand', 'id' => $brand['id'], 'alias' => $brand['alias']]) ?>">
                        <span><?= $brand['name'] ?></span> 
                    </a>
                </h2>
            </div>
        </div>
        <div class="ctn-categories">
            <div class="container">
                <div class="flex-ctn">
                    <div class="banner-categories">
                        <a href="<?= Url::to(['/product/product/brand', 'id' => $brand['id'], 'alias' => $brand['alias']]) ?>">
                            <img src="<?= ClaHost::getImageHost(), $brand['avatar_path'], $brand['avatar_name'] ?>">
                        </a>
                    </div>
                    <div class="product-index-categories">
                        <div class="owl-product-categories owl-carousel owl-theme owl-cate-product">
                            <?php
                            foreach ($products as $product) {
                                ?>
                                <div class="item-product-cate">
                                    <?php if ($product['price_market'] > 0 && $product['price'] > 0) { ?>
                                        <span class="icon-sale"><spam>Sale -<?= ClaLid::getDiscount($product['price_market'], $product['price']) ?>%</spam></span>
                                    <?php } ?>
                                    <div class="img-product-cate">  
                                        <a href="<?= Url::to(['/product/product/detail', 'id' => $product['id'], 'alias' => $product['alias']]) ?>" title="<?= $product['name'] ?>">
                                            <img src="<?= ClaHost::getImageHost(), $product['avatar_path'], $product['avatar_name'] ?>" alt="<?= $product['name'] ?>">
                                        </a>
                                    </div>
                                    <div class="title-product-cate">
                                        <h2><a href="<?= Url::to(['/product/product/detail', 'id' => $product['id'], 'alias' => $product['alias']]) ?>"><?= $product['name'] ?></a></h2>
                                        <span class="price-product"><?= number_format($product['price'], 0, ',', '.') ?> đ</span>
                                    </div>
                                </div>
                                <?php
                            }
                            ?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>