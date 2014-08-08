<div id="user_menu_overlay" onclick="dropDown.show('#userMenu-user', 'userMenu-user')"></div>

<nav id="main_header">
    <div class="wrap cf">
        <div class="row">
            <div class="large-<?php echo ($this->user->id ? 8 : 6); ?> columns">
                <div id="logo"><a href="/"></a></div>
                <?php
                if($this->user->id) { ?>
                    <div id="menu">
                        <?php if($this->user->currentUserData->profile_type == 'startup') { ?><li><a href="/search/users">Intern search</a></li><?php } ?>
                        <?php if($this->user->currentUserData->profile_type == 'startup') { ?><li><a href="/listings/applications">Applicants</a></li><?php } ?>
                        <?php if($this->user->currentUserData->profile_type == 'intern') { ?><li><a href="/search/listings">Positions search</a></li><?php } ?>
                        <?php if($this->user->currentUserData->profile_type == 'intern') { ?><li><a href="/listings/applied">Applications archive</a></li><?php } ?>
                    </div>
                <?php } ?>

                <?php
                // Function to set an active item
                $component = $this->framework->uriSegment1;
                $action    = $this->framework->uriSegment2;
                $currentActive = '/' . $component  . '/' . $action;
                ?>
                <script type="application/javascript">
                    $("#menu a[href='<?php echo $currentActive; ?>']").addClass('current');
                </script>

            </div>

            <div class="large-<?php echo ($this->user->id ? 4 : 6); ?> columns">
                <?php if($this->user->id) { ?>
                <div id="user-menu" class=""><?php $this->load->view('layout/modules/header/user_menu'); ?></div>
                <?php } elseif(!$this->user->id && !$this->framework->uriSegment1) { ?>
                <form method="POST" action="" id="login_form">
                    <input type="email" id="login_username" placeholder="Email" onkeypress="account.login.detectSubmit(event)">
                    <input type="password" id="login_password" placeholder="Password" onkeypress="account.login.detectSubmit(event)">
                    <input type="button" value="Login" class="btn transparent" onclick="account.login.submit()">
                    <div class="cf"><a href="#" class="forgotPassLink" onclick="account.recovery.togglePassRequestTab(); return false;">Forgot your password?</a></div>
                </form>
                <form id="recoverPass_form" style="display: none">
                    <input type="email" id="passRecover_email" placeholder="Enter your email">
                    <input type="button" value="Request new password" class="btn transparent" onclick="account.recovery.sendNewPassRequest()">
                    <div class="cf"><a href="#" class="forgotPassLink" onclick="account.recovery.togglePassRequestTab(); return false;">Back to login</a></div>
                </form>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>