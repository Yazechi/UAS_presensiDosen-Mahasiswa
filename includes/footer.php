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
