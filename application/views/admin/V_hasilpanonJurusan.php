<!DOCTYPE html>
<html>

<head>
    <?php $this->load->view("admin/_partials/V_header.php") ?>
</head>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">

        <header class="main-header">
            <?php $this->load->view("admin/_partials/V_navbar.php") ?>
        </header>
        <div class="content-wrapper">
            <div class="container">
                <?php $this->load->view("admin/_partials/V_breadcrumb.php") ?>
                <!-- Main content -->
                <section class="content">

                    <!-- SELECT2 EXAMPLE -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Hasil Perhitungan Pagu Global (Non Jurusan)</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <?php if ($anggaran['isApproval'] == 1 && $anggaran['keterangan'] != NULL) { ?>
                                <div class="form-group">
                                    <label>KETERANGAN REVISI</label>
                                    <textarea class="form-control" name="keterangan" rows="8" readonly><?= $anggaran['keterangan'] ?></textarea>
                                </div>
                            <?php } ?>

                            <!-- Jikalau keterangan revisi ada dan login dengan akun superadmin, Maka tampilkan tombol edit -->
                            <?php if ($anggaran['isApproval'] == 1 && $this->session->userdata('akses') == 2) : ?>
                                <a class="btn btn-success btn-sm" href="<?= base_url('edit-simulasi-nonjurusan/' . $anggaran['id_simulasi']) ?>"><i class="fa fa-edit"></i> Edit</a>
                            <?php endif; ?>

                            <h4><b>Data Perhitungan</b></h4>
                            <table id="dataHitung" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Unit</th>
                                        <th>Sub Kriteria</th>
                                        <th>Inputan</th>
                                        <th>Rata-rata</th>
                                        <th>Kali Bobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($tabel_hasil != NULL) {
                                        foreach ($tabel_hasil as $key => $value) { ?>
                                            <tr>
                                                <td><?= $value->nama_unit ?></td>
                                                <td><?= $value->nama_subkriteria ?></td>
                                                <td><?= round($value->nilai_input, 2) ?></td>
                                                <td><?= round($value->nilai_rata_rata, 2) ?></td>
                                                <td><?= round($value->nilai_bobot, 2) ?></td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="5">
                                                <center>Tidak ada Data</center>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table><br>

                            <h4><b>Data Hasil</b></h4>
                            <table id="dataHasil" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Unit</th>
                                        <th>Nilai Persentase</th>
                                        <th>Nilai Rupiah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($tabel_hasil_simulasi != NULL) {
                                        foreach ($tabel_hasil_simulasi as $key => $value) { ?>
                                            <tr>
                                                <td><?= $value->nama_unit ?></td>
                                                <td><?= round($value->nilai_persentase, 2) ?></td>
                                                <td><?= number_format($value->nilai_anggaran, 0, ",", ".") ?></td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="5">
                                                <center>Tidak ada Data</center>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Jumlah</th>
                                        <th><?= number_format($total['na'], 0, ",", "."); ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Sisa Pagu</th>
                                        <th><?= number_format($anggaran['nominal_anggaran'] - $total['na'], 0, ",", "."); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="box-footer">
                            <a href="<?php echo base_url('export-simulasi-nonjurusan/' . $anggaran['id_simulasi']) ?>"><button type="button" id="simpan_paJurusan" class="btn btn-success" tabindex="11"><span class="fa fa-download"></span> Export Excel </button></a>
                            <a href="<?php echo base_url('pagu-anggaran-nonjurusan') ?>" class="btn btn-default" tabindex="12"><span class="fa fa-ban"></span> Kembali</a>

                            <?php if ($this->session->userdata('akses') == 1) : ?>
                                <?php if ($anggaran['isApproval'] == 0 || $anggaran['isApproval'] == 1) : ?>
                                    <a class="btn btn-success" href="<?= base_url('Pagu_nonjurusan/validasiData/' . $anggaran['id_simulasi']) ?>"><i class="fa fa-check-square"></i> Validasi</a>
                                <?php endif; ?>
                                <a class="btn btn-danger" href="#" data-toggle="modal" data-target="#modal-default<?= $anggaran['id_simulasi'] ?>"><i class="fa fa-times-circle"></i> Revisi</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- /.box -->

                </section>
                <!-- /.content -->
            </div>
            <!-- /.container -->
        </div>
        <!-- /.content-wrapper -->
        <?php foreach ($simulasi_jurusan as $key => $value) { ?>
            <div class="modal fade" id="modal-default<?= $value->id_simulasi ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Revisi Pagu Anggaran Non-Jurusan</h4>
                        </div>
                        <form action="<?= base_url('Pagu_nonjurusan/UnvalidasiDataKet') ?>" method="post">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea class="form-control" name="keterangan" rows="8" required><?= $value->keterangan ?></textarea>
                                    <input type="hidden" class="form-control" value="<?= $value->id_simulasi ?>" name="idsimulasi" readonly>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        <?php } ?>
        <?php $this->load->view("admin/_partials/V_footer.php") ?>
    </div>
    <!-- ./wrapper -->
    <?php $this->load->view("admin/_partials/V_js.php") ?>
    <script>
        function hanyaAngka(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))

                return false;
            return true;
        }
    </script>
</body>

</html>