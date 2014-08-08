<?php
$currentParent = $this->db->query("SELECT * FROM daystart_todos WHERE id = " . $current->todo_id)->row();
?>

<div class="queue_item_current cf">
    <div class="top"><?php echo $currentParent->title; ?></div>
    <div class="mid"><?php echo convertToHoursMins($current->time_left); ?></div>
    <div class="bottom"></div>
</div>


<?php
foreach($queue as $item)
{
    if($item->active) continue;
    $parent = $this->db->query("SELECT * FROM daystart_todos WHERE id = " . $item->todo_id)->row();
    ?>
    <div class="queue_item cf">
        <div class="left"><?php echo $parent->title; ?></div>
        <div class="right"><?php echo convertToHoursMins($item->time_left); ?></div>
    </div>
    <?php
}
?>