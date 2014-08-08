<?php
$users = array(
//    'Ingrid' => '<img src="http://cdn.decate.no/photos/thumbs/48216/48216_e491f7661c41fe27641dd73db66144ca_150.jpg">',
//    'Isabella' => '<img src="http://cdn.decate.no/photos/thumbs/2477/2477_27b0b9844d1ff98524cf1a6f9d345b6b_150.jpg">',
    'Bjørn' => '<img src="http://cdn.decate.no/photos/thumbs/86274/86274_c6fb9aaf7921f3421a3ba5379a1a0084_150.jpg">',
    'Elizabeth' => '<img src="http://cdn.decate.no/photos/thumbs/7342/7342_4855efb5048a96f2602ab391c8897b3d_150.png">',
    'Martin' => '<img src="http://cdn.decate.no/photos/thumbs/82446/82446_bd8114898bd39194377a563689c9322e_150.jpg">',
    'Alexis' => '<img src="http://cdn.decate.no/photos/thumbs/21722/21722_82d440c5342080e6ebbf6bf2214afd6a_150.jpg">',
    'Mark' => '<img src="http://cdn.decate.no/photos/thumbs/28429/28429_ae1656cdf442844650f9cde5eda8ff2b_150.jpg">',
    'Amber' => '<img src="http://cdn.decate.no/photos/thumbs/69475/69475_ca67c9359fe934ced111ed381ea995ec_150.jpg">'
);
?>

<div class="block">
    <div class="title">New applicants</div>
    <div class="body cf noPadding">
    <?php
     $x = 0;
    foreach($users as $name => $img)
    {
        if($x++ == 5) break;
        ?>
        <div class="user_row cf">
            <div class="left"><?php echo $img; ?></div>
            <div class="right">
                <div class="top"><?php echo $name; ?></div>
                <div class="bottom">Sent an application for "Developer needed in Sofia"</div>
            </div>
        </div>
        <?php
    }
    ?>
    </div>
</div>