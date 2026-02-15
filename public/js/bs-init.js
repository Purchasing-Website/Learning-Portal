document.addEventListener('DOMContentLoaded', function() {

	var toastTriggers = document.querySelectorAll('[data-bs-toggle="toast"]');

	for (let toastTrigger of toastTriggers) {
		toastTrigger.addEventListener('click', function () {
			var toastSelector = toastTrigger.getAttribute('data-bs-target');

			if (!toastSelector) return;

			try {
				var toastEl = document.querySelector(toastSelector);

				if (!toastEl) return;

				var toast = new bootstrap.Toast(toastEl);
				toast.show();
			}
			catch(e) {
				console.error(e);
			}
		})
	}

	var charts = document.querySelectorAll('[data-bss-chart]');

	for (var chart of charts) {
		chart.chart = new Chart(chart, JSON.parse(chart.dataset.bssChart));
	}
}, false);