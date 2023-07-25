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
                            <h3 class="box-title">Pagu Anggaran Jurusan</h3>
                        </div>
                        <!-- /.box-header -->
                        <form id="dynamicContent" method="post">
                            <div class="box-body">
                                <?php if (!isset($_SESSION['tahun_simulasi'])) { ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Tahun</label>
                                                <input type="text" class="form-control" id="tahun_hitungan" name="tahun_hitungan" onkeypress="return hanyaAngka(event)" tabindex="1" autofocus required>
                                                <input type="hidden" value="1" name="jenis_simulasi" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nominal Anggaran Polije (Rp)</label>
                                                <input type="text" class="input_numb form-control" id="ttk_nominal_anggaran" name="ttk_nominal_anggaran" onchange="copyHarga()" onkeyup="copyHarga()" tabindex="1" autofocus required>
                                                <input type="hidden" name="nominal_anggaran" id="nominal_anggaran" readonly>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Tahun</label>
                                                <input type="text" class="form-control" id="tahun_hitungan" name="tahun_hitungan" onkeypress="return hanyaAngka(event)" tabindex="1" value="<?= $_SESSION['tahun_simulasi'] ?>" autofocus required readonly>
                                                <input type="hidden" value="1" name="jenis_simulasi" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nominal Anggaran Polije (Rp)</label>
                                                <input type="text" class="input_numb form-control" id="ttk_nominal_anggaran" name="ttk_nominal_anggaran" onchange="copyHarga()" onkeyup="copyHarga()" value="<?= number_format($_SESSION['nominal_anggaran'], 0, ",", ".") ?>" tabindex="1" autofocus required readonly>
                                                <input type="hidden" name="nominal_anggaran" id="nominal_anggaran" value="<?= $_SESSION['nominal_anggaran'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Pilih Jurusan</label>
                                            <select id="selectJurusan" class=" form-control" name="jurusan" required>
                                                <option value="" selected>Pilih Jurusan</option>
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
                                                <?php foreach ($subkriteria as $key => $val) : ?>
                                                    <option value="<?= $val->id_subkriteria ?>"><?= $val->nama_subkriteria; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Inputan Form -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label id="labelInput"></label>
                                            <input type="text" class="form-control" id="inputan" name="inputan" tabindex="1" onkeypress="return hanyaAngka(event)" tabindex="1" autofocus required>
                                            <button id="btnCari" class="btn btn-success" type="submit"><i class="fa fa-search"></i> Cari Prodi</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="list-prodi" class="box-footer" style="display: none"></div>

                            <div class="box-footer">
                                <div class="col-md-12">
                                    <span class="text-bold text-danger">Keterangan inputan berdasarkan sub kriteria</span>
                                    <ul>
                                        <li>Jumlah Mahasiswa: inputan berupa angka, contoh: 80 </li>
                                        <li>Jumlah SDM: inputan berupa angka, contoh: 30</li>
                                        <li>Jumlah Prodi: inputan terisi otomatis. Silahkan klik tombol simpan untuk melanjutkan</li>
                                        <li>Capaian Akreditasi Prodi: Silahkan tekan tombol "Cari Prodi" terlebih dahulu. Inputan Akreditasi berupa huruf, contoh: A atau Unggul</li>
                                        <li>Capaian Kinerja (Peringkat): inputan berupa angka, contoh: 1</li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button id="btnSubmit" class="btn btn-primary" tabindex="11"><span class="fa fa-save"></span> Simpan </button>
                                <!-- <a href="<?php echo base_url('pagu-anggaran-jurusan') ?>" class="btn btn-default" tabindex="12"><span class="fa fa-ban"></span> Batal</a> -->
                            </div>
                        </form>
                        <div class="box-body">
                            <h4><b>Data Perhitungan</b></h4>
                            <table id="dataHitung" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Jurusan</th>
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
                            <a href="<?php echo base_url('Pagu_jurusan/hitungSimulasi') ?>"><button type="button" id="simpan_paJurusan" class="btn btn-primary" tabindex="11"><span class="fa fa-save"></span> Hitung </button></a>
                            <a href="<?php echo base_url('pagu-anggaran-jurusan') ?>" class="btn btn-default" tabindex="12"><span class="fa fa-ban"></span> Batal</a>
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

        var subKategori = document.getElementById("subKategori");
        var selectJurusan = document.getElementById("selectJurusan");
        var btnCari = document.getElementById("btnCari");
        var btnSubmit = document.getElementById("btnSubmit");
        var inputan = document.getElementById("inputan");
        var labelInput = document.getElementById("labelInput");
        var listProdi = document.getElementById("list-prodi");
        var dynamicContent = document.getElementById("dynamicContent");

        // Menyembunyikan komponen sebelum digunakan
        inputan.type = "hidden";
        btnCari.style.display = "none";

        // Event listener pada elemen form (dynamicContent) untuk event "submit".
        dynamicContent.addEventListener("submit", function(event) {
            event.preventDefault(); // Mencegah pengiriman form secara default.

            // Lakukan validasi form atau tindakan lain yang diperlukan.
            if (<?= $id_simulasi ?> == 0) {
                setFormActionAndSubmit(); // Fungsi untuk mengubah action dan mengirimkan form.
            } else {
                fetch("<?= site_url('cek-data/') ?>" + <?= $id_simulasi ?> + "/" + selectJurusan.value + "/" + subKategori.value)
                    .then(response => response.json())
                    .then(data => {
                        // Proses validasi
                        // Panggil fungsi untuk mengubah action dan mengirimkan form berdasarkan hasil validasi.
                        var jumlah = data[0].jumlah;

                        if (jumlah > 0) {
                            var decision = confirm("Anda Telah mengisi data ini. Ingin menimpa dengan data yang baru?");

                            if (decision) {
                                // User menyetujui untuk menimpa data
                                setFormActionAndSubmit(); // Fungsi untuk mengubah action dan mengirimkan form.
                            } else {
                                // User tidak menyetujui, Lewati tahapan pengiriman form.
                            }
                        } else {
                            // Melanjutkan pengiriman form jika data merupakan data baru. 
                            setFormActionAndSubmit(); // Fungsi untuk mengubah action dan mengirimkan form.
                        }
                    })
                    .catch(error => {
                        console.error("Terjadi kesalahan:", error);
                    });
            }
        });

        subKategori.addEventListener("change", function() {
            console.log(subKategori.value);

            if (subKategori.value == 4) {
                labelInput.innerHTML = 'Inputan';
                inputan.type = "hidden";
                btnCari.style.display = "block";


                btnCari.addEventListener("click", function() {
                    event.preventDefault();

                    // Ambil nilai id_jurusan dari select option
                    var idJurusan = selectJurusan.value;

                    // Lakukan permintaan Ajax untuk mengambil data prodi berdasarkan id_jurusan
                    // Anda dapat menggunakan XMLHttpRequest atau fetch
                    fetch("<?= site_url('cari-prodi/') ?>" + idJurusan)
                        .then(response => response.json())
                        .then(data => {
                            // Manipulasi elemen HTML untuk menampilkan data prodi
                            var listProdi = document.getElementById("list-prodi");
                            listProdi.style.display = "block";

                            // Bersihkan wadah sebelum mengisi ulang
                            listProdi.innerHTML = `
                                <div class="box-header form-group">
                                    <h3 class="box-title">Daftar Prodi</h3>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label><b>PROGRAM STUDI</b></label>
                                    </div>
                                    <div class="col-md-2">
                                        <label><b>NILAI AKREDITASI</b></label>
                                    </div>
                                </div>
                            `;

                            // Looping melalui data prodi dan tambahkan elemen baru ke dalam wadah
                            data.forEach(function(prodi) {
                                var namaProdi = prodi.nama_prodi;

                                // Buat elemen baru untuk setiap prodi
                                var prodiElement = document.createElement("div");
                                prodiElement.className = "row form-group";
                                prodiElement.innerHTML = `
                                    <div class="col-md-4">
                                        <label>${namaProdi}</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="input_prodi[]" tabindex="1" autofocus required>
                                    </div>
                                `;

                                // Tambahkan elemen prodi ke dalam wadah daftar prodi
                                listProdi.appendChild(prodiElement);
                            });

                            if (data.length === 0) {
                                var emptyMessage = document.createElement("div");
                                emptyMessage.className = "row form-group";
                                emptyMessage.innerHTML = `
                                    <div class="col-md-12">
                                        <b><center>BELUM ADA DATA PRODI</center></b>
                                    </div>
                                `;

                                listProdi.appendChild(emptyMessage);
                            }
                        })
                        .catch(error => {
                            console.error("Terjadi kesalahan:", error);
                        });
                });

                btnSubmit.addEventListener("click", function(event) {
                    event.preventDefault();

                    var inputs = document.getElementsByName("input_prodi[]");
                    var dataString = ";";

                    // Looping melalui setiap input prodi
                    for (var i = 0; i < inputs.length; i++) {
                        var input = inputs[i];
                        var value = input.value;

                        // Menggabungkan data dari setiap input dengan tanda titik-koma
                        dataString += value + ";";
                    }

                    // Hapus tanda titik-koma terakhir jika ada
                    if (dataString.length > 0) {
                        dataString = dataString.slice(0, -1);
                    }

                    // Tambahkan dataString ke dalam input tersembunyi pada form
                    var hiddenInput = document.createElement("input");
                    hiddenInput.type = "hidden";
                    hiddenInput.name = "inputan";
                    hiddenInput.value = dataString.toUpperCase();
                    dynamicContent.appendChild(hiddenInput);

                    fetch("<?= site_url('cek-data/') ?>" + <?= $id_simulasi ?> + "/" + selectJurusan.value + "/" + subKategori.value)
                        .then(response => response.json())
                        .then(data => {
                            // Proses validasi
                            // Panggil fungsi untuk mengubah action dan mengirimkan form berdasarkan hasil validasi.
                            var jumlah = data[0].jumlah;

                            if (jumlah > 0) {
                                var decision = confirm("Anda Telah mengisi data ini. Ingin menimpa dengan data yang baru?");

                                if (decision) {
                                    // User menyetujui untuk menimpa data
                                    setFormActionAndSubmit(); // Fungsi untuk mengubah action dan mengirimkan form.
                                } else {
                                    // User tidak menyetujui, Lewati tahapan pengiriman form.
                                }
                            } else {
                                // Melanjutkan pengiriman form jika data merupakan data baru. 
                                setFormActionAndSubmit(); // Fungsi untuk mengubah action dan mengirimkan form.
                            }
                        })
                        .catch(error => {
                            console.error("Terjadi kesalahan:", error);
                        });
                });

            } else if (subKategori.value == 0) {
                labelInput.innerHTML = '';
                inputan.type = "hidden";
                btnCari.style.display = "none";
                listProdi.style.display = "none";
            } else if (subKategori.value == 3) {
                var idJurusan = selectJurusan.value;

                if (idJurusan == 0) {
                    alert('Mohon untuk memilih Jurusan terlebih dahulu!');
                } else {
                    fetch("<?= site_url('hitung-prodi/') ?>" + idJurusan)
                        .then(response => response.json())
                        .then(data => {
                            // Manipulasi elemen HTML untuk menampilkan jumlah prodi
                            labelInput.innerHTML = 'Inputan';

                            inputan.setAttribute("type", "text");
                            inputan.setAttribute("readonly", "readonly");

                            btnCari.style.display = "none";
                            listProdi.style.display = "none";

                            var prodi = data[0];
                            inputan.value = prodi.jumlah_prodi;
                        })
                        .catch(error => {
                            console.error("Terjadi kesalahan:", error);
                        });
                }
            } else {
                labelInput.innerHTML = 'Inputan';
                inputan.type = "text";
                btnCari.style.display = "none";
                listProdi.style.display = "none";

                inputan.removeAttribute("readonly");
                inputan.value = "";
            }
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

        function setFormActionAndSubmit() {
            dynamicContent.action = "<?= site_url('Pagu_jurusan/add_inputan') ?>"; // Ubah action form
            dynamicContent.submit(); // Mengirimkan form.
        }
    </script>
</body>

</html>