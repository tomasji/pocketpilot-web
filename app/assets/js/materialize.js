import M from 'materialize-css'

// navbar init
document.addEventListener('DOMContentLoaded', () => {
	const elems = document.querySelectorAll('.sidenav')
	M.Sidenav.init(elems)
})

// stop pulse on click
const pulses = document.querySelectorAll('.pulse')
pulses.forEach(pulse =>
	pulse.addEventListener('click', (e) => {
		e.target.parentElement.classList.remove('pulse')
	})
)
