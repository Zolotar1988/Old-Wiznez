<?php $sidebar = alx_sidebar_secondary(); ?>

<div class="sidebar s2">

    <a class="sidebar-toggle" title="<?php _e('Expand Sidebar','hueman'); ?>"><i class="fa icon-sidebar-toggle"></i></a>

    <div class="sidebar-content">

        <?php
        # Get database
        include "/var/www/wp-config.php";
        $database = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        # Fetch members
        $members   = array();
        $myId      = get_current_user_id();
        $query     = $database->query("SELECT * FROM wp_users WHERE ID != $myId ORDER BY RAND() LIMIT 4");
        while($row = $query->fetch_assoc()) $members[] = json_decode(json_encode($row));

        # Fetch groups
        $groups     = array();
        $myId      = get_current_user_id();
        $query     = $database->query("SELECT * FROM wp_bp_groups ORDER BY RAND() LIMIT 4");
        while($row = $query->fetch_assoc()) $groups[] = json_decode(json_encode($row));
        ?>

        <div class="widget sidebar_usersImages">
            <h3>Sugested contacts</h3>
            <?php foreach($members as $member) echo '<a href="/members/' . get_userdata($member->ID)->user_nicename . '"><img src="' . bp_core_fetch_avatar(array('item_id' => $member->ID, 'html' => false)) . '"></a>'; ?>
        </div>

        <div class="widget sidebar_usersImages">
            <h3>Groups</h3>
            <?php foreach($groups as $group) echo '<a href="/groups/' . $group->slug . '"><img src="' . bp_core_fetch_avatar(array('item_id' => $group->id, 'html' => false, 'object' => 'group')) . '"></a>'; ?>
        </div>

    </div><!--/.sidebar-content-->

</div><!--/.sidebar-->