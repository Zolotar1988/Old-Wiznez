<?php $randomGoToString = $userData->id . rand(0,99999); ?>

<div class="row-item row-item-user">
    <header><a href="/profile/<?php echo $userData->id; ?>"><img src="<?php echo $userData->thumb_large; ?>"></a></header>

    <section>
        <div class="username"><?php echo $userData->name; ?><?php if($this->user->userData($userData->id)->isOnline) echo ' · <span class="isOnline">Online</span>'; ?></div>
        <div class="meta"><?php echo ucfirst($userData->city); ?>, <?php echo $userData->country; ?></div>
    </section>

    <div class="overlay row-item-actions" onclick="goToUrl_<?php echo $randomGoToString; ?>()">
        <a href="/profile/<?php echo $userData->id; ?>" class="btn">View</a>
    </div>

</div>

<script type="application/javascript">function goToUrl_<?php echo $randomGoToString; ?>() { window.location.href = '/profile/<?php echo $userData->id; ?>'; }</script>