<div class="user-menu-block">
    <?php if($this->user->currentUserData->profile_type == 'startup') { ?><a href="/profile/<?php echo $this->user->id; ?>?action=create_listing" class="btn cta">New application</a><?php } ?>
</div>



<div class="user-menu-block item_badge<?php echo ($this->notifications->notificationCount > 0 ? ' active' : ''); ?>">
    <a href="<?php echo ($this->user->currentUserData->profile_type == 'startup' ? '/listings/applications' : '/listings/invitations'); ?>" class="notification">
        <span class="icon">
            <i class="icon-bell"></i>
            <span class="badge"><?php echo ($this->notifications->notificationCount ? $this->notifications->notificationCount : ''); ?></span>
        </span>
    </a>
</div>



<div class="user-menu-block avatar">
    <a href="#" class="dropdown" data-dropdown="userMenu"><img src="<?php echo $this->user->currentUserData->thumb; ?>"></a>
</div>

<ul id="userMenu" class="small content f-dropdown" data-dropdown-content>
    <li><a href="/profile/<?php echo $this->user->id; ?>">View your profile</a></li>
    <li><a href="/profile/edit">Edit your profile</a></li>
    <li><a href="/account/manage">Edit your account</a></li>
    <li><a href="/account/logout">Logout</a></li>
</ul>
