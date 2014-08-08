<div class="block">
    <div class="title">Edit your account</div>
    <div class="body">
        <form method="POST" action="/account/account_actions_controller/manage">
            <?php echo generateInputFields($this->account->account_editingFields(), $this->user->currentUserData); ?>
            <?php echo generate_submitButton('Edit account'); ?>
        </form>
    </div>
</div>