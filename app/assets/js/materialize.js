import M from 'materialize-css'

// navbar init
document.addEventListener('DOMContentLoaded', () => {
	const elems = document.querySelectorAll('.sidenav')
	M.Sidenav.init(elems)
})

// modals
document.addEventListener('DOMContentLoaded', function() {
	const elems = document.querySelectorAll('.modal')
	M.Modal.init(elems)
})

// stop pulse on click
document.addEventListener('DOMContentLoaded', function() {
	const pulses = document.querySelectorAll('.pulse')
	pulses.forEach(pulse =>
		pulse.addEventListener('click', (e) => {
			e.target.parentElement.classList.remove('pulse')
		})
	)
})

// toast from flash message
document.addEventListener('DOMContentLoaded', function() {
	const flashes = document.getElementById('snippet--flashes')
	const message = flashes.children[0].dataset.message
	M.toast({ html: message, displayLength: 2500 })
})
