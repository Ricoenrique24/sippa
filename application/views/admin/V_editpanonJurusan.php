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
                    <?php if ($tabel_parent['isApproval'] == 0 && $tabel_parent['keterangan'] != NULL) { ?>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">KETERANGAN REVISI</h3>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <textarea class="form-control" name="keterangan" rows="8" readonly><?= $tabel_parent['keterangan'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- SELECT2 EXAMPLE -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Pagu Anggaran (Non Jurusan)</h3>
                        </div>
                        <!-- /.box-header -->
                        <form action="<?= site_url('Pagu_nonjurusan/prosesEditParent') ?>" method="post">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <input type="text" class="form-control" id="tahun_hitungan" name="tahun_hitungan" onkeypress="return hanyaAngka(event)" tabindex="1" value="<?= $tabel_parent['tahun_simulasi'] ?>" autofocus required>
                                            <input type="hidden" value="<?= $tabel_parent['id_simulasi'] ?>" name="id_simulasi" readonly required>
                                            <input type="hidden" value="2" name="jenis_simulasi" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nominal Anggaran Polije (Rp)</label>
                                            <input type="text" class="input_numb form-control" id="ttk_nominal_anggaran" name="ttk_nominal_anggaran" onchange="copyHarga()" onkeyup="copyHarga()" value="<?= number_format($tabel_parent['nominal_anggaran'], 0, ",", ".") ?>" tabindex="1" autofocus required>
                                            <input type="hidden" name="nominal_anggaran" id="nominal_anggaran" value="<?= $tabel_parent['nominal_anggaran'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" id="simpan_paJurusan" class="btn btn-primary" style="margin-top: 24px;" tabindex="11"><span class="fa fa-save"></span> Simpan </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form id="dynamicContent" action="<?= site_url('Pagu_nonjurusan/edit_inputan') ?>" method="post">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="hidden" value="<?= $tabel_parent['id_simulasi'] ?>" name="id_simulasi" readonly required>
                                        <input type="hidden" value="<?= $tabel_parent['tahun_simulasi'] ?>" name="tahun_hitungan" readonly required>
                                        <input type="hidden" value="1" name="jenis_simulasi" readonly required>
                                        <input type="hidden" name="nominal_anggaran" value="<?= $tabel_parent['nominal_anggaran'] ?>" readonly>
                                        <div class="form-group">
                                            <label>Pilih Unit</label>
                                            <select id="selectJurusan" class="form-control" name="jurusan" required>
                                                <option value="" selected>Pilih Unit</option>
                                                <?php foreach ($jurusan as $key => $val) { ?>
                                                    <option value="<?= $val->id_unit ?>"><?= $val->nama_unit; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Pilih Sub Kriteria</label>
                                            <select id="subKategori" class="form-control" name="subkriteria" required>
                                                <option value="" selected>Pilih Sub Kriteria</option>
                                                <?php foreach ($subkriteria as $key => $val) { ?>
                                                    <option value="<?= $val->id_subkriteria ?>"><?= $val->nama_subkriteria; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label id="labelInput">Inputan</label>
                                            <input type="text" class="form-control" id="inputan" name="inputan" tabindex="1" onkeypress="return hanyaAngka(event)" tabindex="1" autofocus required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-8">
                                        <span class="text-bold text-danger">Catatan: semua inputan sub kriteria berupa angka</span>
                                    </div>

                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" id="btnSubmit" class="btn btn-primary" tabindex="11"><span class="fa fa-save"></span> Simpan </button>
                                <!-- <a href="<?php echo base_url('pagu-anggaran-nonjurusan') ?>" class="btn btn-default" tabindex="12"><span class="fa fa-ban"></span> Batal</a> -->
                            </div>
                        </form>
                        <div class="box-body">
                            <h4><b>Data Perhitungan</b></h4>
                            <table id="dataHitung" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Non Jurusan</th>
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
                            </table>
                        </div>
                        <div class="box-footer">
                            <a href="<?php echo base_url('Pagu_nonjurusan/hitungSimulasiEdit/' . $tabel_parent['id_simulasi']) ?>"><button type="button" id="simpan_paJurusan" class="btn btn-primary" tabindex="11"><span class="fa fa-save"></span> Hitung Ulang </button></a>
                            <a href="<?php echo base_url('pagu-anggaran-nonjurusan') ?>" class="btn btn-default" tabindex="12"><span class="fa fa-ban"></span> Kembali</a>
                        </div>
                    </div>
                    <!-- /.box -->

                </section>
                <!-- /.content -->
            </div>
            <!-- /.container -->
        </div>
        <!-- /.content-wrapper -->
        <?php $this->load->view("admin/_partials/V_footer.php") ?>
    </div>
    <!-- ./wrapper -->
    <?php $this->load->view("admin/_partials/V_js.php") ?>
    <script>
        var subKategori = document.getElementById("subKategori");
        var inputan = document.getElementById("inputan");
        var labelInput = document.getElementById("labelInput");
        var btnSubmit = document.getElementById("btnSubmit");
        var selectJurusan = document.getElementById("selectJurusan");
        var dynamicContent = document.getElementById("dynamicContent");

        // Menyembunyikan komponen sebelum digunakan
        inputan.type = "hidden";
        labelInput.innerHTML = '';

        subKategori.addEventListener("change", function() {
            console.log(subKategori.value);

            if (subKategori.value == 0) {
                labelInput.innerHTML = '';
                inputan.type = "hidden";
            } else {
                labelInput.innerHTML = 'Inputan';
                inputan.type = "text";
            }
        });

        btnSubmit.addEventListener("click", function(event) {
            event.preventDefault();

                fetch("<?= site_url('cek-data/') ?>" + <?= $tabel_parent['id_simulasi'] ?> + "/" + selectJurusan.value + "/" + subKategori.value)
                    .then(response => response.json())
                    .then(data => {
                        var jumlah = data[0].jumlah;

                        // Mengecek Apakah ada data pada tabel perhitungan? 
                        if (jumlah > 0) {
                            var decision = confirm("Anda Telah mengisi data ini. Ingin menimpa dengan data yang baru?");

                            if (decision == true) {
                                // User menyetujui untuk menimpa data
                                dynamicContent.submit();
                            } else {
                                // User tidak menyetujui, Lewati tahapan submit form
                                // Bagian ini dibiarkan kosong agar pengiriman data digagalkan.
                            }
                        } else {
                            // Melanjutkan submit form jika data merupakan data baru. 
                            dynamicContent.submit();
                        }
                    })
                    .catch(error => {
                        console.error("Terjadi kesalahan:", error);
                    });
        });

        $(document).ready(function() {
            $('.input_numb').on('keypress', function(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
                return true;
            });
            $('.input_numb').on('input', function() {
                if (this.value) {
                    var x = this.value.split('.').join('').toString();
                    this.value = num_format(x);
                }
            });
        });

        function num_format(x) {
            var reverse = x.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return ribuan;
        }

        function hanyaAngka(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))

                return false;
            return true;
        }

        function copyHarga() {
            var nominal_anggaran = document.getElementById('ttk_nominal_anggaran').value;

            if (nominal_anggaran.includes('.')) {
                nominal_anggaran = nominal_anggaran.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
            }

            $('#nominal_anggaran').val(nominal_anggaran);

        }
    </script>
</body>

</html>