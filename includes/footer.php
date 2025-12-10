<<<<<<< HEAD
	</main>

	<footer class="app-footer">
		<p>&copy; 2024 Sistem Presensi Dosen & Mahasiswa. All rights reserved.</p>
		<p id="current-date"></p>
	</footer>

	<script src="<?= ($_SERVER['DOCUMENT_ROOT'] ?? '') ?>/SistemPresensi/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const dateElement = document.getElementById('current-date');
			if (dateElement) {
				dateElement.textContent = new Date().toLocaleDateString('id-ID', {
					weekday: 'long',
					year: 'numeric',
					month: 'long',
					day: 'numeric'
				});
			}
		});
	</script>
</body>
</html>
=======
    </main>

    <!-- Footer -->
    <footer class="app-footer">
        <div class="footer-content">
            <p>&copy; 2024 Sistem Presensi Dosen & Mahasiswa. All rights reserved.</p>
            <p id="current-date"></p>
        </div>
    </footer>

    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
        // Set current date
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    </script>
</body>
</html>
>>>>>>> 45dcb6cd97da2ba7dc48c44310df4950700fda78
