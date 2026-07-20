<div class="row-fluid">
    <div id="footer" class="span12"> <a class="pecolor" href="#" target="_blank">
            <?= date('Y'); ?> &copy; v0.1 — Executive</a></div>
</div>
<!--end-Footer-part-->
<script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url(); ?>assets/js/matrix.js"></script>
<script src="<?= base_url('assets/js/sweetalert2.all.min.js') ?>"></script>

<!-- FullCalendar -->
<link href='<?php echo base_url(); ?>assets/css/fullcalendar.min.css' rel='stylesheet' />
<script src='<?php echo base_url(); ?>assets/js/fullcalendar.min.js'></script>
<script src='<?php echo base_url(); ?>assets/js/fullcalendar/locales/pt-br.js'></script>

<!-- Custom CSS Vuexy-->
<script src="<?= base_url(); ?>assets/vendor/libs/popper/popper.js"></script>
<script src="<?= base_url(); ?>assets/vendor/libs/node-waves/node-waves.js"></script>
<script src="<?= base_url(); ?>assets/vendor/libs/pickr/pickr.js"></script>
<script src="<?= base_url(); ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="<?= base_url(); ?>assets/vendor/libs/hammer/hammer.js"></script>
<script src="<?= base_url(); ?>assets/vendor/libs/i18n/i18n.js"></script>
<script src="<?= base_url(); ?>assets/vendor/js/menu.js"></script>
<!-- Vendors JS -->
<script src="<?= base_url(); ?>assets/vendor/libs/chartjs/chartjs.js"></script>
<script src="<?= base_url(); ?>assets/vendor/libs/apex-charts/apexcharts.js"></script>
<script src="<?= base_url(); ?>assets/js/charts-apex.js"></script>
<script src="<?= base_url(); ?>assets//js/charts-chartjs-legend.js"></script>
<script src="<?= base_url(); ?>assets//js/charts-chartjs.js"></script>
<script src="<?= base_url(); ?>assets/js/app-logistics-dashboard.js"></script>
<script src="<?= base_url(); ?>assets/js/ui-modals.js"></script>

<!-- Page JS -->
<script src="<?= base_url(); ?>assets/js/cards-statistics.js"></script>

<!-- Main JS -->
<script src="<?= base_url(); ?>assets/js/main.js"></script>

<!-- Select2 CSS e JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>
<script type="text/javascript">
    $(document).ready(function() {
        var dataTableEnabled = '<?php echo $configuration['control_datatable']; ?>';
        if(dataTableEnabled == '1') {
            $('#tabela').dataTable( {
                "ordering": false,
                "language": {
                    "url": "<?= base_url(); ?>assets/js/dataTable_pt-br.json"
                }
            } );
        }
    } );
</script>
</html>
