    <script src="/kost/assets/UI/Admins/js/core/jquery-3.7.1.min.js"></script>
    <script src="/kost/assets/UI/Admins/js/core/popper.min.js"></script>
    <script src="/kost/assets/UI/Admins/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="/kost/assets/UI/Admins/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="/kost/assets/UI/Admins/js/plugin/chart.js/chart.min.js"></script>


    <!-- jQuery Sparkline -->
    <script src="/kost/assets/UI/Admins/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="/kost/assets/UI/Admins/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="/kost/assets/UI/Admins/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="/kost/assets/UI/Admins/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="/kost/assets/UI/Admins/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="/kost/assets/UI/Admins/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="/kost/assets/UI/Admins/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="/kost/assets/UI/Admins/js/kaiadmin.min.js"></script>

<script>
    $(function() {
        $("#data").DataTable({
            "paging"        : true,
            "searching"     : true,
            "responsive"    : true,
            "lengthChange"  : true,
            "ordering"      : true,
            "info"          : true,
            "autoWidth"     : false,
        });

        $("#laporan").DataTable({
            "paging"        : true,
            "searching"     : true,
            "responsive"    : true,
            "lengthChange"  : true,
            "ordering"      : true,
            "info"          : true,
            "autoWidth"     : false,
            "buttons"       : ["copy", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#laporan_wrapper .col-md-6:eq(0)');
    })
</script>

<script>
    let idleTime = 0;

    document.onmousemove    = resetTimer;
    document.onkeypress     = resetTimer;
    document.ontouchstart   = resetTimer;
    document.ontouchmove    = resetTimer;

    function resetTimer() {
        idleTime = 0;
    }

    setInterval(function () {
        idleTime++;
        if (idleTime >= 3600) { // 20 minutes
            Swal.fire({
                icon                : 'warning',
                title               : 'Sesi Berakhir',
                text                : 'Sesi anda telah berakhir. Silakan login kembali.',
                showConfirmButton: false,
                timer: 2000
            }).then(function() {
                window.location.href = '../controller/logout';
            });
        }
    }, 1000);
</script>

<!-- Toggle Password -->
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.parentElement.querySelector('.toggle-icon');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

<script>

    document.addEventListener('contextmenu', e => e.preventDefault());

    document.addEventListener("keydown", function (e) {
        if (e.ctrlKey && ['u', 's'].includes(e.key.toLowerCase())) e.preventDefault();
        if ((e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'i') || e.key === 'F12') e.preventDefault();
    });

    document.addEventListener("copy", function(e) {
        e.preventDefault();
        const selection = window.getSelection().toString();
        const hash = CryptoJS.SHA256(selection).toString(); // Hasil hash
        e.clipboardData.setData("text/plain", hash);
    });

</script>


</body>

</html>