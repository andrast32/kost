<?php

    $users = $mysqli->query(" 

        SELECT 
            id_user, 
            nama_user 

        FROM 
            user 

        WHERE 
            role = 'User' 
            AND deleted != 1 
            AND id_user 

        NOT IN (

            SELECT 
                id_user 

            FROM 
                pemesanan 

            WHERE 
                status_kontrak 

            IN ('Menunggu', 'Aktif')
        )

        ORDER BY nama_user ASC 

    "); 

    $kamar_kosong = $mysqli->query(" 
        SELECT * FROM kamar WHERE status = 'Kosong' ORDER BY kode_kamar ASC 
    ");

    $fasilitas_tambahan = $mysqli->query(" 
        SELECT * FROM fasilitas WHERE stok > 0 AND deleted != 1 ORDER BY nama_fasilitas ASC 
    ");

    $kamar_list = $kamar_kosong->fetch_all(MYSQLI_ASSOC); $fasilitas_list =

    $fasilitas_tambahan->fetch_all(MYSQLI_ASSOC); $user_list =

    $users->fetch_all(MYSQLI_ASSOC); 

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">
                            <?= htmlspecialchars($h1) ?>
                        </h4>
                    </div>
                </div>

                <div class="card-body">

                    <div id="panel-daftar-kamar">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row g-4">
                                    <?php if (count($kamar_list) >
                                    0): ?>
                                    <?php foreach($kamar_list as $kamar): ?>
                                    <div class="col-md-4 col-lg-3">
                                        <div
                                            class="card kamar-card h-100"
                                            onclick="pilihKamar(<?= htmlspecialchars(json_encode($kamar)) ?>)"
                                        >
                                            <img
                                                src="/kost/assets/uploads/kamar/<?= htmlspecialchars($kamar['foto']) ?>"
                                                class="card-img-top"
                                                style="height: 180px; object-fit: cover"
                                            />
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <?= htmlspecialchars($kamar['kode_kamar']) ?>
                                                    |
                                                    <?= htmlspecialchars($kamar['khusus']) ?>
                                                </h5>

                                                <p
                                                    class="card-text text-danger fw-bold"
                                                >
                                                    Rp.
                                                    <?= number_format($kamar['harga'], 2, ",",".") ?>
                                                    /bulan
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <p class="text-center text-muted">
                                        Tidak ada kamar yang tersedia saat ini.
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="panel-pemesanan-detail">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <button
                                    type="button"
                                    class="btn btn-round btn-primary btn-border ms-auto mb-3"
                                    onclick="kembaliKeDaftar()"
                                >
                                    <i class="fas fa-angle-double-left"></i> Kembali 
                                </button>
                                <form
                                    action="settings/functions/add/add_pemesanan"
                                    method="POST"
                                >
                                    <input
                                        type="hidden"
                                        name="id_kamar"
                                        id="detail_id_kamar"
                                    />

                                    <div id="fasilitas-hidden-inputs"></div>

                                    <div class="row g-4">
                                        <div class="col-lg-7">
                                            <h3 id="detail_kode_kamar"></h3>
                                            <img
                                                id="detail_foto_kamar"
                                                src=""
                                                class="gallery-main-img mb-3"
                                                alt="Foto utama kamar"
                                            />
                                            <p id="detail_deskripsi_kamar"></p>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="text-danger">
                                                        <span
                                                            id="detail_harga_kamar"
                                                        ></span>
                                                        <small class="text-muted"
                                                            >/ bulan</small
                                                        >
                                                    </h4>
                                                    <hr />
                                                    <div class="mb-3">
                                                        <label
                                                            for="id_user"
                                                            class="form-label fw-bold"
                                                            >Pilih Penyewa</label
                                                        >
                                                        <select
                                                            class="form-select"
                                                            id="id_user"
                                                            name="id_user"
                                                            required
                                                        >
                                                            <option
                                                                value=""
                                                                disabled
                                                                selected
                                                            >
                                                                -- Pilih salah satu --
                                                            </option>
                                                            <?php foreach($user_list as $user): ?>
                                                            <option
                                                                value="<?= $user['id_user'] ?>"
                                                            >
                                                                <?= htmlspecialchars($user['nama_user']) ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-6">
                                                            <label
                                                                for="tanggal_mulai_kontrak"
                                                                class="form-label"
                                                                >Tgl Mulai</label
                                                            >
                                                            <input
                                                                type="date"
                                                                class="form-control"
                                                                id="tanggal_mulai_kontrak"
                                                                name="tanggal_mulai_kontrak"
                                                                required
                                                            />
                                                        </div>
                                                        <div class="col-6">
                                                            <label
                                                                for="tanggal_akhir_kontrak"
                                                                class="form-label"
                                                                >Tgl Akhir</label
                                                            >
                                                            <input
                                                                type="date"
                                                                class="form-control"
                                                                id="tanggal_akhir_kontrak"
                                                                name="tanggal_akhir_kontrak"
                                                                required
                                                            />
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="dropdown addons-dropdown mb-3"
                                                    >
                                                        <button
                                                            class="btn btn-outline-secondary dropdown-toggle w-100"
                                                            type="button"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false"
                                                            data-bs-auto-close="outside"
                                                        >
                                                            Tambah Fasilitas (Add-ons)
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <div class="addons-list">
                                                                <?php foreach($fasilitas_list as $fasilitas): ?>
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center p-2 border-bottom"
                                                                >
                                                                    <div
                                                                        class="d-flex align-items-center"
                                                                    >
                                                                        <img
                                                                            src="/kost/assets/uploads/fasilitas/<?= htmlspecialchars($fasilitas['foto']) ?>"
                                                                            class="addon-item-img"
                                                                            alt="<?= htmlspecialchars($fasilitas['nama_fasilitas']) ?>"
                                                                        />
                                                                        <div>
                                                                            <span
                                                                                class="fw-bold"
                                                                                ><?= htmlspecialchars($fasilitas['nama_fasilitas']) ?></span
                                                                            >
                                                                            <small
                                                                                class="text-muted d-block"
                                                                                >Rp
                                                                                <?= number_format($fasilitas['harga']) ?></small
                                                                            >
                                                                            <smal
                                                                                class="text-muted d-block"
                                                                            >
                                                                                Sisa
                                                                                <?= $fasilitas['stok'] ?>
                                                                            </smal>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="d-flex align-items-center"
                                                                    >
                                                                        <div
                                                                            class="quantity-control"
                                                                        >
                                                                            <button
                                                                                type="button"
                                                                                class="btn-minus"
                                                                                data-id="<?= $fasilitas['id_fasilitas'] ?>"
                                                                            >
                                                                                -
                                                                            </button>
                                                                            <input
                                                                                type="number"
                                                                                class="quantity-input addon-quantity"
                                                                                id="quantity_<?= $fasilitas['id_fasilitas'] ?>"
                                                                                name="jumlah_fasilitas[<?= $fasilitas['id_fasilitas'] ?>]"
                                                                                value="0"
                                                                                min="0"
                                                                                max="<?= $fasilitas['stok'] ?>"
                                                                                data-id="<?= $fasilitas['id_fasilitas'] ?>"
                                                                                data-harga="<?= $fasilitas['harga'] ?>"
                                                                                readonly
                                                                            />
                                                                            <button
                                                                                type="button"
                                                                                class="btn-plus"
                                                                                data-id="<?= $fasilitas['id_fasilitas'] ?>"
                                                                            >
                                                                                +
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr />
                                                    <div
                                                        class="d-flex justify-content-between"
                                                    >
                                                        <span>Biaya Kamar/bulan</span>
                                                        <strong id="harga-kamar-display"
                                                            >Rp 0</strong
                                                        >
                                                    </div>
                                                    <div
                                                        class="d-flex justify-content-between"
                                                    >
                                                        <span>Biaya Add-ons/bulan</span>
                                                        <strong
                                                            id="harga-addons-display"
                                                            >Rp 0</strong
                                                        >
                                                    </div>
                                                    <hr />
                                                    <div
                                                        class="d-flex justify-content-between fs-5"
                                                    >
                                                        <strong
                                                            >Total Biaya Bulanan</strong
                                                        >
                                                        <strong
                                                            id="total-bulanan-display"
                                                            class="text-success"
                                                            >Rp 0</strong
                                                        >
                                                    </div>
                                                    <div
                                                        class="text-end text-muted mt-2"
                                                    >
                                                        Durasi Sewa:
                                                        <span
                                                            id="durasi_display"
                                                            class="fw-bold"
                                                            >-</span
                                                        ><br />
                                                        <strong class="fs-5"
                                                            >Estimasi Total Biaya:
                                                            <span
                                                                id="grand_total_display"
                                                                class="fw-bold text-primary"
                                                                >Rp 0</span
                                                            ></strong
                                                        >
                                                    </div>
                                                    <button
                                                        type="submit"
                                                        class="btn btn-primary w-100 mt-3 py-2 fw-bold"
                                                    >
                                                        <i
                                                            class="fas fa-check-circle me-2"
                                                        ></i
                                                        >Buat Kontrak
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const panelDaftarKamar =
            document.getElementById("panel-daftar-kamar");
        const panelPemesananDetail = document.getElementById(
            "panel-pemesanan-detail"
        );

        let hargaKamarSaatIni = 0;
        let totalBiayaBulanan = 0;

        window.pilihKamar = function (kamarData) {
            panelDaftarKamar.style.display = "none";
            panelPemesananDetail.style.display = "block";
            window.scrollTo(0, 0);

            document.getElementById("detail_id_kamar").value =
                kamarData.id_kamar;
            document.getElementById("detail_kode_kamar").textContent =
                kamarData.kode_kamar;
            document.getElementById(
                "detail_foto_kamar"
            ).src = `/kost/assets/uploads/kamar/${kamarData.foto}`;
            document.getElementById(
                "detail_deskripsi_kamar"
            ).textContent = kamarData.deskripsi;
            document.getElementById(
                "detail_harga_kamar"
            ).textContent = `Rp ${parseInt(
                kamarData.harga
            ).toLocaleString("id-ID")}`;
            hargaKamarSaatIni = parseInt(kamarData.harga);

            document
                .querySelectorAll(".addon-quantity")
                .forEach((input) => (input.value = 0));
            hitungTotalBulanan();
        };

        window.kembaliKeDaftar = function () {
            panelDaftarKamar.style.display = "block";
            panelPemesananDetail.style.display = "none";
        };

        const hargaAddonsDisplay = document.getElementById(
            "harga-addons-display"
        );
        const totalBulananDisplay = document.getElementById(
            "total-bulanan-display"
        );
        const quantityInputs =
            document.querySelectorAll(".addon-quantity");
        const fasilitasHiddenInputsContainer = document.getElementById(
            "fasilitas-hidden-inputs"
        );
        const startDateInput = document.getElementById(
            "tanggal_mulai_kontrak"
        );
        const endDateInput = document.getElementById(
            "tanggal_akhir_kontrak"
        );
        const durasiDisplay = document.getElementById("durasi_display");
        const grandTotalDisplay = document.getElementById(
            "grand_total_display"
        );

        function hitungTotalBulanan() {
            let totalAddons = 0;
            fasilitasHiddenInputsContainer.innerHTML = ""; // Kosongkan kontainer input hidden

            quantityInputs.forEach((input) => {
                const jumlah = parseInt(input.value) || 0;
                if (jumlah > 0) {
                    const harga = parseInt(
                        input.getAttribute("data-harga")
                    );
                    const idFasilitas = input.getAttribute("data-id");
                    totalAddons += harga * jumlah;

                    // Buat input hidden sebanyak jumlah yang dipesan
                    for (let i = 0; i < jumlah; i++) {
                        const hiddenInput =
                            document.createElement("input");
                        hiddenInput.type = "hidden";
                        hiddenInput.name = "fasilitas[]";
                        hiddenInput.value = idFasilitas;
                        fasilitasHiddenInputsContainer.appendChild(
                            hiddenInput
                        );
                    }
                }
            });

            totalBiayaBulanan = hargaKamarSaatIni + totalAddons;
            document.getElementById(
                "harga-kamar-display"
            ).textContent = `Rp ${hargaKamarSaatIni.toLocaleString(
                "id-ID"
            )}`;
            hargaAddonsDisplay.textContent = `Rp ${totalAddons.toLocaleString(
                "id-ID"
            )}`;
            totalBulananDisplay.textContent = `Rp ${totalBiayaBulanan.toLocaleString(
                "id-ID"
            )}`;

            hitungGrandTotal();
        }

        function hitungGrandTotal() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            if (
                startDate &&
                endDate &&
                new Date(endDate) > new Date(startDate)
            ) {
                const date1 = new Date(startDate);
                const date2 = new Date(endDate);
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(
                    diffTime / (1000 * 60 * 60 * 24)
                );
                const bulanSewa = Math.ceil(diffDays / 30);
                const grandTotal = totalBiayaBulanan * bulanSewa;

                durasiDisplay.textContent = `${bulanSewa} bulan (${diffDays} hari)`;
                grandTotalDisplay.textContent = `Rp ${grandTotal.toLocaleString(
                    "id-ID"
                )}`;
            } else {
                durasiDisplay.textContent = "-";
                grandTotalDisplay.textContent = "Rp 0";
            }
        }

        // =======================================================================
        // PERUBAHAN DI SINI: Event listener untuk tombol plus/minus
        // =======================================================================
        document.addEventListener("click", function (event) {
            if (
                event.target.classList.contains("btn-plus") ||
                event.target.classList.contains("btn-minus")
            ) {
                const button = event.target;
                const id = button.getAttribute("data-id");
                const input = document.getElementById(`quantity_${id}`);
                let currentValue = parseInt(input.value);
                const max = parseInt(input.getAttribute("max"));
                const min = parseInt(input.getAttribute("min"));

                if (button.classList.contains("btn-plus")) {
                    if (currentValue < max) {
                        input.value = currentValue + 1;
                    }
                } else if (button.classList.contains("btn-minus")) {
                    if (currentValue > min) {
                        input.value = currentValue - 1;
                    }
                }
                hitungTotalBulanan(); // Panggil ulang perhitungan setelah nilai berubah
            }
        });
        // =======================================================================

        startDateInput.addEventListener("change", hitungGrandTotal);
        endDateInput.addEventListener("change", hitungGrandTotal);
    });
</script>
