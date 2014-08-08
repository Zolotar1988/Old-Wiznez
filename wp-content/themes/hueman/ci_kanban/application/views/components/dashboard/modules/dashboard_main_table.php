<?php
$config     = $this->config->item('techpear');
$fields     = $config['listings_fields_forTable'];
$analFields = $config['listings_fields_forTable'];
?>


<?php
if($this->input->get('created'))
{
    ?>
    <div class="listingCreatedMsg">
        <span>Your new listing <strong><?php echo $newListing->title; ?></strong> has been created but is not active. Click <a href="#">here</a> to pay and activate it</span>
    </div>
<?php
}
?>


<div class="block">
<div class="title">Your listings</div>
    <div class="body noPadding">
        <table>
            <thead>
            <tr>
                <?php foreach($fields as $key => $data) echo '<th scope="col">' . $data['title'] . '</th>'; ?>
                <th scope="col">Active</th>
                <th scope="col">View</th>
                <th scope="col">Edit</th>
            </tr>
            </thead>


            <tbody>
            <?php foreach($listings as $listing)
            {
                ?>
            <tr>
                <?php foreach($fields as $key => $data) echo '<td>' . $listing->$key . '</td>'; ?>
                <td><?php echo ($listing->isActive ? 'Yes' : 'No'); ?></td>
                <td><a href="/listings/<?php echo $listing->id; ?>">View</a></td>
                <td><a href="/listings/edit/<?php echo $listing->id; ?>">Edit</a></td>
            </tr>
                <?php
            }
            ?>
            </tbody>


            <tfoot>
            <tr>
                <?php
                foreach($fields as $key => $data)
                {
                    $value = 0;
                    if(!isset($data['analField']))
                    {
                        echo '<td>-</td>';
                    }
                    else
                    {
                        foreach($listings as $listing)
                        {
                            $value += $listing->$key;
                        }
                    }

                    if(isset($data['analField'])) echo "<td>$value</td>";
                }
                ?>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php
if($this->input->get('created'))
{
    ?>
    <script type="application/javascript">
        $(".listing_row-<?php echo $this->input->get('created'); ?>").addClass('new');
    </script>
    <?php
}