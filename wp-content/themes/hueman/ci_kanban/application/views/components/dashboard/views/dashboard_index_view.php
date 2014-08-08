<div class="row">
    <div class="large-8 columns">
        <?php $this->load->view('components/dashboard/modules/dashboard_main_table', $moduleData); ?>
    </div>

    <div class="large-4 columns">
        <?php $this->load->view('components/dashboard/modules/dashboard_sidebar', $moduleData); ?>
    </div>
</div>