class Controls {
	constructor(track, table) {
		this.track = track
		this.table = table
		this._bind()
	}

	_bind() {
		document.addEventListener('DOMContentLoaded', (e) => {
			const controls = e.target.querySelector('.save-track > #frm-form')
			const submit = controls.querySelector('input[type="submit"][name="save"]')
			const hidden = controls.querySelector('input[name="waypoints"]')
			submit.addEventListener('click', () => {
				hidden.value = JSON.stringify(this.track.getWaypoints().map((wp) => wp.getLatLng()))
			})
		})
		document.addEventListener('DOMContentLoaded', (e) => {
			const speed = e.target.querySelector('.controls-speed input[type="text"][name="speed"]')
			speed.addEventListener('input', (e) => {
				this.table.recalculateTimes(e.target.value)
			})
		})
	}
}

export { Controls }
