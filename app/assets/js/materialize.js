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

// tooltips
document.addEventListener('DOMContentLoaded', function() {
	const elems = document.querySelectorAll('.tooltipped')
	M.Tooltip.init(elems)
})

// toast from flash message
document.addEventListener('DOMContentLoaded', function() {
	const flashes = document.getElementById('snippet--flashes')
	if (flashes.children && flashes.children.length) {
		const message = flashes.children[0].dataset.message
		M.toast({ html: message, displayLength: 2500 })
	}
})

// confirm dialogs
document.addEventListener('DOMContentLoaded', function() {
	const confirms = document.querySelectorAll('[data-confirm]')
	confirms.forEach(el => {
		const cb = el.dataset.positive
		const selector = el.dataset.confirm
		if (!cb || !selector) return
		const ele = document.querySelector(selector)
		M.Modal.init(ele)
		el.addEventListener('click', function() {
			const positiveBtn = ele.querySelector('.modal-content > a.positive')
			positiveBtn.setAttribute('href', cb)
			const inst = M.Modal.getInstance(ele)
			inst.open()
		})
	})
})
